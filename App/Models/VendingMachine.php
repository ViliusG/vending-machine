<?php

namespace App\Models;

use App\Traits\Conversion;
use Exception;

class VendingMachine
{
    use Conversion;

    protected $usersMoney;
    protected $stock;
    protected $cashRegister;

    /**
     * VendingMachine constructor.
     * @param Stock $stock
     * @param CashRegister $cashRegister
     */
    public function __construct(Stock $stock, CashRegister $cashRegister)
    {
        $this->stock = $stock;
        $this->cashRegister = $cashRegister;
        $this->usersMoney = 0;
    }

    /**
     * @return int
     */
    public function getUsersMoney(): int
    {
        return $this->usersMoney;
    }

    /**
     * @return array
     */
    public function getStock(): array
    {
        return $this->stock->getStock();
    }

    /**
     * @param string $money
     * @return array
     * @throws Exception
     */
    public function addMoney(string $money): array
    {
        $this->cashRegister->addMoneyToBank($money);
        $this->usersMoney += $this->convertToCents($money);

        return [
            'tendered' => number_format($this->usersMoney/100, 2)
        ];
    }

    /**
     * @param int $slot
     * @return array
     * @throws Exception
     */
    public function buy(int $slot): array
    {
        $item = $this->stock->getItem($slot);
        if ($item['quantity'] == 0 || $item['price'] > $this->usersMoney) {
            throw new Exception('Item cannot be sold');
        }
        $change = $this->cashRegister->giveChange($this->usersMoney, $item['price']);
        $item = $this->stock->removeFromStock($slot);
        $this->usersMoney = 0;

        return [
            'change' => $change,
            'item' => $item
        ];
    }
}

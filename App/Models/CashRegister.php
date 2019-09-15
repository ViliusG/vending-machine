<?php

namespace App\Models;

use App\Traits\Conversion;
use Exception;
use InvalidArgumentException;

class CashRegister
{
    use Conversion;

    protected $bank;

    /**
     * CashRegister constructor.
     * @param $bank
     */
    public function __construct($bank)
    {
        $this->bank = $this->validateInitialMoney($bank);
    }

    /**
     * @return array
     */
    public function getBank(): array
    {
        return $this->bank;
    }

    /**
     * @param $money
     * @throws Exception
     */
    public function addMoneyToBank($money): void
    {
        if (!array_key_exists($money, $this->bank)) {
            throw new Exception('Invalid coin');
        }
        $this->bank[$money]++;
    }

    /**
     * @param array $initialMoney
     * @return array
     */
    public function validateInitialMoney(array $initialMoney): array
    {
        foreach ($initialMoney as $coin => $quantity) {
            if (
                !is_string($coin) ||
                !is_int($quantity) ||
                !preg_match('/\d/', $coin) //if string contains a number
            ) {
                throw new InvalidArgumentException('Invalid Input');
            }
        }

        return $initialMoney;
    }

    /**
     * @param int $usersMoney
     * @param int $price
     * @return array
     * @throws Exception
     */
    public function giveChange(int $usersMoney, int $price):array
    {
        $changeNeeded = $usersMoney - $price;
        $change = [];
        foreach ($this->bank as $coin => $amount) {
            for ($i = 0; $i < $amount; $i++) {
                if ($this->convertToCents($coin) <= $changeNeeded) {
                    $change[$coin] = isset($change[$coin]) ? $change[$coin]+1 : 1;
                    $changeNeeded -= $this->convertToCents($coin);
                }
            }
        }
        if ($changeNeeded != 0) {
            throw new Exception('Sorry, change is not available');
        }
        $this->removeFromBank($change);

        return $change;
    }

    /**
     * @param array $change
     */
    public function removeFromBank(array $change): void
    {
        foreach ($change as $coin => $amount) {
            $this->bank[$coin] -= $amount;
        }
    }
}

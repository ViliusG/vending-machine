<?php

namespace App\Models;

use Exception;
use InvalidArgumentException;

class Stock
{
    protected $stock;

    /**
     * Stock constructor.
     * @param array $stock
     */
    public function __construct(array $stock)
    {
        $this->stock = $this->validateStock($stock);
    }

    /**
     * @return array
     */
    public function getStock(): array
    {
        return $this->stock;
    }

    /**
     * @param int $slotNumber
     * @return array
     */
    public function getItem(int $slotNumber): array
    {
        return $this->stock[$slotNumber-1];
    }


    /**
     * @param array $stock
     * @return array
     */
    public function validateStock(array $stock): array
    {
        foreach ($stock as $item) {
            if (
                count($item) != 3 ||
                !array_key_exists('name', $item) ||
                !array_key_exists('quantity', $item) ||
                !array_key_exists('price', $item) ||
                !is_string($item['name']) ||
                !is_int($item['quantity']) ||
                !is_int($item['price'])
            ) {
                throw new InvalidArgumentException('Invalid Stock');
            }
        }

        return $stock;
    }

    /**
     * @param int $slot
     * @return string
     * @throws Exception
     */
    public function removeFromStock(int $slot): string
    {
        if ($this->stock[$slot-1]['quantity'] == 0) {
            throw new Exception('Out of stock');
        }
        $this->stock[$slot-1]['quantity'] = $this->stock[$slot-1]['quantity']-1;

        return $this->stock[$slot-1]['name'];
    }
}

<?php

namespace Tests;

use App\Models\Stock;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StockTest extends TestCase
{
    protected $stock;
    protected $items;

    public function setUp(): void
    {
        $this->items = [
            [
                'name' => 'Snickers',
                'quantity' => 2,
                'price' => 140
            ],
            [
                'name' => 'Twix',
                'quantity' => 5,
                'price' => 180
            ]
        ];
        $this->stock = new Stock($this->items);
    }

    public function testStockInitialization():void
    {

        $this->assertEquals($this->stock->getStock(), $this->items);
    }

    public function testValidateInputsWhenValid():void
    {
        $validStock = $this->items;
        $this->assertEquals($this->stock->validateStock($validStock), $this->items);
        $invalidStock = [
            [
                'name' => ['chocolate bar'],
                'quantity' => 'two',
                'price' => 'pound of salt'
            ]
        ];
        $this->expectException(InvalidArgumentException::class);
        $this->stock->validateStock($invalidStock);
    }

    public function testGetItem():void
    {
        $item = [
            'name' => 'Snickers',
            'quantity' => 2,
            'price' => 140
        ];
        $this->assertEquals($this->stock->getItem(1), $item);
    }

    public function testRemoveFromStock()
    {
        $removedItem = $this->stock->removeFromStock(1);
        $this->assertEquals($removedItem, 'Snickers');
        $remainingItems = [
            [
                'name' => 'Snickers',
                'quantity' => 1,
                'price' => 140
            ],
            [
                'name' => 'Twix',
                'quantity' => 5,
                'price' => 180
            ]
        ];
        $this->assertEquals($this->stock->getStock(), $remainingItems);
    }
}

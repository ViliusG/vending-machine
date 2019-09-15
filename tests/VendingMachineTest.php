<?php

namespace Tests;

use App\Models\CashRegister;
use App\Models\Stock;
use App\Models\VendingMachine;
use Exception;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    protected $vendingMachine;

    public function setUp():void
    {
        $mockedStock = $this->createMock(Stock::class);
        $mockedCashRegister = $this->createMock(CashRegister::class);
        $this->vendingMachine = new VendingMachine($mockedStock, $mockedCashRegister);
    }

    public function testAddUsersMoney():void
    {
        $this->assertEquals('2.00', $this->vendingMachine->addMoney('$2')['tendered']);
        $this->assertEquals('4.00', $this->vendingMachine->addMoney('$2')['tendered']);
    }

    public function testAddMoneyToBank():void
    {
        $mockedStock = $this->createMock(Stock::class);
        $mockedCashRegister = $this->createMock(CashRegister::class);
        $mockedCashRegister->expects($this->once())
            ->method('addMoneyToBank');

        $vendingMachine = new VendingMachine($mockedStock, $mockedCashRegister);
        $vendingMachine->addMoney('$2');
    }

    public function testBuySucess():void
    {
        $mockedStock = $this->createMock(Stock::class);
        $mockedStock->expects($this->once())
            ->method('getItem')
            ->willReturn([
                'name' => 'Snickers',
                'quantity' => 2,
                'price' => 140
            ]);
        $mockedStock->expects($this->once())
            ->method('removeFromStock');

        $mockedCashRegister = $this->createMock(CashRegister::class);
        $mockedCashRegister->expects($this->once())
            ->method('giveChange');

        $vendingMachine = new VendingMachine($mockedStock, $mockedCashRegister);
        $vendingMachine->addMoney('$2');
        $vendingMachine->buy(1);
    }

    public function testBuyOutOfStock()
    {
        $mockedStock = $this->createMock(Stock::class);
        $mockedStock->expects($this->once())
            ->method('getItem')
            ->willReturn([
                'name' => 'Snickers',
                'quantity' => 0,
                'price' => 140
            ]);
        $mockedCashRegister = $this->createMock(CashRegister::class);
        $vendingMachine = new VendingMachine($mockedStock, $mockedCashRegister);
        $this->expectException(Exception::class);
        $vendingMachine->buy(1);
    }

    public function testBuyNotEnoughMoney()
    {
        $mockedStock = $this->createMock(Stock::class);
        $mockedStock->expects($this->once())
            ->method('getItem')
            ->willReturn([
                'name' => 'Snickers',
                'quantity' => 0,
                'price' => 140
            ]);
        $mockedCashRegister = $this->createMock(CashRegister::class);
        $vendingMachine = new VendingMachine($mockedStock, $mockedCashRegister);
        $this->expectException(Exception::class);
        $vendingMachine->addMoney('$1');
        $vendingMachine->buy(1);
    }
}

<?php

namespace Tests;

use App\Models\VendingMachine;
use App\Views\TerminalView;
use PHPUnit\Framework\TestCase;

class TerminalViewTest extends TestCase
{
    protected $terminalView;

    public function setUp():void
    {
        $mockedVendingMachine = $this->createMock(VendingMachine::class);
        $this->terminalView = new TerminalView($mockedVendingMachine);
    }

    public function testInitialViewIsPrinted():void
    {
        $this->assertTrue(is_string($this->terminalView->printInitialView()));
    }

    public function testGetStockString()
    {
        $stock = [
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
        $mockedVendingMachine = $this->createMock(VendingMachine::class);
        $mockedVendingMachine->method('getStock')
            ->willReturn($stock);
        $terminalView = new TerminalView($mockedVendingMachine);
        $string = "Slot 1 - 2 x Snickers = 1.40\nSlot 2 - 5 x Twix = 1.80\n";
        $this->assertEquals($string, $terminalView->getStockString());
    }
}
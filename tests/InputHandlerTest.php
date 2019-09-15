<?php


namespace Tests;

use App\Models\InputHandler;
use App\Models\VendingMachine;
use Exception;
use PHPUnit\Framework\TestCase;

class InputHandlerTest extends TestCase
{
    protected $inputHandler;

    public function setUp():void
    {
        $mockedVendingMachine = $this->createMock(VendingMachine::class);
        $this->inputHandler = new InputHandler($mockedVendingMachine);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage Invalid coin
     */
    public function ifInputIsValid()
    {
        $this->inputHandler->validateInput('$8');
        $this->inputHandler->validateInput('slot3');
        $this->expectException(Exception::class);
        $this->inputHandler->validateInput('5$');
    }

    public function testIfAttemptedToAddMoney()
    {
        $mockedVendingMachine = $this->createMock(VendingMachine::class);
        $mockedVendingMachine->expects($this->once())
            ->method('addMoney');
        $inputHandler = new InputHandler($mockedVendingMachine);
        $inputHandler->redirectInput('$2');
    }

    public function testIfAttemptedBuy()
    {
        $mockedVendingMachine = $this->createMock(VendingMachine::class);
        $mockedVendingMachine->expects($this->once())
            ->method('buy');
        $inputHandler = new InputHandler($mockedVendingMachine);
        $inputHandler->redirectInput('slot1');
    }

    public function testStringNormaliser()
    {
        $string1 = '$ 8 ';
        $string2 = 'Slot 2';
        $this->assertEquals($this->inputHandler->normalize($string1), '$8');
        $this->assertEquals($this->inputHandler->normalize($string2), 'slot2');
    }
}
<?php


namespace Tests;

use App\Models\CashRegister;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CashRegisterTest extends TestCase
{
    protected $cashRegister;
    protected $initialMoney;

    public function setUp(): void
    {
        $this->initialMoney = [
            '$2' => 4,
            '$1' => 2,
            '50c' => 3,
            '20c' => 2
        ];
        $this->cashRegister = new CashRegister($this->initialMoney);
    }

    public function testCashRegisterInitialization(): void
    {
        $this->assertEquals($this->cashRegister->getBank(), $this->initialMoney);
    }

    public function testAddMoneyToBank(): void
    {
        $this->cashRegister->addMoneyToBank('$2');
        $moneyAfterAdding = $this->initialMoney;
        $moneyAfterAdding['$2']++;
        $this->assertEquals($this->cashRegister->getBank(), $moneyAfterAdding);
        $this->cashRegister->addMoneyToBank('50c');
        $moneyAfterAdding['50c']++;
        $this->assertEquals($this->cashRegister->getBank(), $moneyAfterAdding);
    }

    public function testAddInvalidMoneyToBank():void
    {
        $this->expectException(Exception::class);
        $this->cashRegister->addMoneyToBank('$5');
    }

    public function testConvertInvalidMoney():void
    {
        $this->expectException(Exception::class);
        $this->cashRegister->addMoneyToBank('5$');
    }

    public function testInitialMoneyValidator():void
    {
        $this->assertEquals($this->cashRegister->validateInitialMoney($this->initialMoney), $this->initialMoney);
        $this->expectException(InvalidArgumentException::class);
        $invalidInitialMoney = [
            5 => 'heap',
            'fifty' => 'bunch',
            'buck' => 'few'
        ];
        $this->cashRegister->validateInitialMoney($invalidInitialMoney);
    }

    public function testGiveChangeSuccess():void
    {
        $change = [
            '$1' => 1,
            '20c' => 1
        ];
        $this->assertEquals($change, $this->cashRegister->giveChange(300, 180));
    }

    public function testGiveChangeFail():void
    {
        $this->expectException(Exception::class);
        $this->cashRegister->giveChange(300, 185);
    }

    public function testRemoveFromBank(): void
    {
        $toRemove = [
            '$2' => 2,
            '20c' => 1
        ];
        $remainingBank = [
            '$2' => 2,
            '$1' => 2,
            '50c' => 3,
            '20c' => 1
        ];
        $this->cashRegister->removeFromBank($toRemove);
        $this->assertEquals($this->cashRegister->getBank(), $remainingBank);
    }
}

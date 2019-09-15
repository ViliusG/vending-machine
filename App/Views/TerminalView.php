<?php


namespace App\Views;

use App\Models\VendingMachine;

final class TerminalView implements ViewInterface
{
    protected $vendingMachine;

    /**
     * TerminalView constructor.
     * @param VendingMachine $vendingMachine
     */
    public function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    /**
     * @return string
     */
    public function printInitialView(): string
    {
        $message =  "\nWelcome to the Vending Machine simulator!\n";
        $message .=  "The vending machine contains the following products:\n\n";
        $message .=  $this->getStockString() ."\n";
        $message .=  "The vending machine accepts the following coins:\n";
        $message .=  "5c  10c  20c  50c  $1  $2\n";
        $message .=  "Please insert coins one at a time and pressing enter after each, e.g. $2 or 5c\n\n";
        $message .=  "To vend from a slot type slot command, e.g. slot 1";

        return $message;
    }

    /**
     * @return string
     */
    public function getStockString(): string
    {
        $stock = '';
        $slot = 1;
        foreach ($this->vendingMachine->getStock() as $key => $item) {
            $stock.= 'Slot ' . $slot . ' - ' . $item['quantity'] . ' x ' . $item['name'] . ' = ' . number_format($item['price']/100, 2) . "\n";
            $slot++;
        }

        return $stock;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        echo "\n\nEnter = ";
        return fgets(STDIN);
    }

    /**
     * @param array $output
     * @return string
     */
    public function getOutput(array $output):string
    {
        $text = "\n";
        if (array_key_exists('tendered', $output)) {
            $text .= "Tendered = {$output['tendered']}";
        }
        if (array_key_exists('change', $output) && array_key_exists('item', $output)) {
            $text .= "Enjoy!\n\nItem = {$output['item']}\n";
            if (empty($output['change'])) {
                $text .= "No change";
                return $text;
            }
            $text .= "Change = ";
            foreach ($output['change'] as $coin => $amount) {
                for ($i = 0; $i < $amount; $i++) {
                    $text .= "$coin ";
                }
            }
        }

        return $text;
    }
}

<?php

use App\Models\CashRegister;
use App\Models\InputHandler;
use App\Models\Stock;
use App\Models\VendingMachine;
use App\Views\TerminalView;

require_once __DIR__ . '/vendor/autoload.php';

$stock = [
    [
        'name' => 'Snickers',
        'quantity' => 3,
        'price' => 105
    ],
    [
        'name' => 'Bounty',
        'quantity' => 0,
        'price' => 100
    ],
    [
        'name' => 'Mars',
        'quantity' => 1,
        'price' => 125
    ],
    [
        'name' => 'Twix',
        'quantity' => 2,
        'price' => 200
    ],
    [
        'name' => 'Wispa',
        'quantity' => 2,
        'price' => 180
    ],
    [
        'name' => 'Twirl',
        'quantity' => 2,
        'price' => 75
    ],
    [
        'name' => 'Yorkie',
        'quantity' => 3,
        'price' => 180
    ],
    [
        'name' => 'Aero',
        'quantity' => 0,
        'price' => 180
    ],
    [
        'name' => 'Double Decker',
        'quantity' => 3,
        'price' => 75
    ],
    [
        'name' => 'Galaxy',
        'quantity' => 2,
        'price' => 180
    ],
    [
        'name' => 'Crunchie',
        'quantity' => 3,
        'price' => 180
    ],
    [
        'name' => 'Picnic',
        'quantity' => 2,
        'price' => 125
    ],
    [
        'name' => 'Kit Kat',
        'quantity' => 2,
        'price' => 200
    ],
    [
        'name' => 'Lion Bar',
        'quantity' => 3,
        'price' => 180
    ],
    [
        'name' => 'Oreo',
        'quantity' => 2,
        'price' => 200
    ],
    [
        'name' => 'Toffee Crisp',
        'quantity' => 1,
        'price' => 200
    ],
    [
        'name' => 'Boost',
        'quantity' => 1,
        'price' => 150
    ]
];
$bank = [
    '$2'    => 2,
    '$1'    => 4,
    '50c'   => 2,
    '20c'   => 3,
    '10c'   => 4,
    '5c'    => 2
];

$vendingMachine = new VendingMachine(new Stock($stock), new CashRegister($bank));
$inputHandler = new InputHandler($vendingMachine);
$view = new TerminalView($vendingMachine);

echo $view->printInitialView();
do {
    try {
        $input = $view->getInput();
        $input = $inputHandler->validateInput($input);
        $output = $inputHandler->redirectInput($input);

        switch ($inputHandler->getOperation($input)) {
            case 'AddMoney':
                $vendingMachine->addMoney($input);
                break;
        }

        echo $view->getOutput($output);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} while (true); //exit option is not defined in specifications
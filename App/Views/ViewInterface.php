<?php


namespace App\Views;


interface ViewInterface
{
    /**
     * @return string
     */
    public function printInitialView(): string;

    /**
     * @return string
     */
    public function getStockString(): string;

    /**
     * @return string
     */
    public function getInput(): string;

    /**
     * @param array $output
     * @return string
     */
    public function getOutput(array $output): string;
}
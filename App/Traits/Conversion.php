<?php


namespace App\Traits;

use Exception;

trait Conversion
{
    /**
     * @param string $input
     * @return int
     * @throws Exception
     */
    public function convertToCents(string $input): int
    {
        if (substr($input, 0, 1) == '$') {
            return substr($input, 1) * 100;
        }
        if (substr($input, -1, 1) == 'c') {
            return substr($input, 0, -1);
        }
        throw new Exception('Invalid coin');
    }
}

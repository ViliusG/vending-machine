<?php

namespace App\Models;

use InvalidArgumentException;

/**
 * Class InputHandler
 * @package App\Models
 */
class InputHandler
{
    /**
     * @param string $input
     * @return string
     */
    public function normalize(string $input): string
    {
        return trim(strtolower(str_replace(' ', '', $input)));
    }

    /**
     * @param string $input
     * @return string
     */
    public function validateInput(string $input): string
    {
        $input = $this->normalize($input);
        if ( preg_match('/^slot\d+$|^\d+[c]$|^[$]\d$/', $input) === 0 ) {
            throw new InvalidArgumentException('Your input in invalid');
        }

        return $input;
    }
}

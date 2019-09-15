<?php


namespace Tests;


use App\Traits\Conversion;
use Exception;
use PHPUnit\Framework\TestCase;

class ConversionTraitTest extends TestCase
{
    use Conversion;

    public function testConversionToCents()
    {
        $cents1 = $this->convertToCents('$2');
        $cents2 = $this->convertToCents('50c');
        $this->assertEquals($cents1, 200);
        $this->assertEquals($cents2, 50);
    }

    public function testConversionToCentsFail()
    {
        $this->expectException(Exception::class);
        $this->convertToCents('2$');
    }
}
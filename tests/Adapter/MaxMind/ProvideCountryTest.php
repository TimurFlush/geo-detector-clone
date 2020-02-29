<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\MaxMind;

class ProvideCountryTest extends TestCase
{
    use ClientAddressProvider;

    /**
     * @dataProvider clientAddressProvider
     */
    public function testProvidingCountry(string $ip, array $expected)
    {
        $adapter  = new MaxMind(PATH_TO_MAXMIND_BASE);
        $expected = $expected['country']['iso'];
        $actual   = $adapter->provideCountryCode($ip);

        $this->assertEquals($expected, $actual);
    }
}

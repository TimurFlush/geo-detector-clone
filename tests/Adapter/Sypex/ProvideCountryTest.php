<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\Sypex;

class ProvideCountryTest extends TestCase
{
    use ClientAddressProvider;

    /**
     * @dataProvider clientAddressProvider
     */
    public function testProvidingCountry(string $ip, array $expected)
    {
        $adapter = new Sypex(PATH_TO_SYPEX_BASE);

        $expected = $expected['country']['iso'];
        $actual   = $adapter->provideCountryCode($ip);

        $this->assertEquals($expected, $actual);
    }
}

<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\Sypex;

class ProvideCountriesBatchTest extends TestCase
{
    use BatchProvider;

    /**
     * @dataProvider batchProvider
     */
    public function testProvidingCountriesInBatchMode(array $IPs, array $expected)
    {
        $adapter = new Sypex(PATH_TO_SYPEX_BASE);
        $IPs     = $adapter->provideCountryCodesBatch($IPs);

        if (empty($IPs)) {
            $this->fail('Provided countries list is empty');
        }

        foreach ($IPs as $IP => $country) {
            $this->assertEquals($expected[$IP]['country']['iso'], $country);
        }
    }
}

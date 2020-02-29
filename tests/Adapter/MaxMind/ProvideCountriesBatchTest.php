<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\MaxMind;

class ProvideCountriesBatchTest extends TestCase
{
    use BatchProvider;

    /**
     * @dataProvider batchProvider
     */
    public function testProvidingCountriesInBatchMode(array $IPs, array $expected)
    {
        $adapter = new MaxMind(PATH_TO_MAXMIND_BASE);
        $IPs     = $adapter->provideCountryCodesBatch($IPs);

        if (empty($IPs)) {
            $this->fail('Provided countries list is empty');
        }

        foreach ($IPs as $IP => $country) {
            $this->assertEquals($expected[$IP]['country']['iso'], $country);
        }
    }
}

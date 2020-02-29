<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\SypexAPI;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\SypexAPI;

class ProvideAllBatchTest extends TestCase
{
    use BatchProvider;

    /**
     * @dataProvider batchProvider
     */
    public function testProvidingAllDataInBatchMode(array $IPs, array $expected)
    {
        $adapter = new SypexAPI();
        $list    = $adapter->provideAllBatch($IPs);

        if (empty($list)) {
            $this->fail('Provided geo data list is empty');
        }

        foreach ($list as $actual) {
            $actualCountry = $actual->getCountry();
            $actualRegion = $actual->getRegion();
            $actualCity = $actual->getCity();
            $ip = $actual->getClientAddress();

            /*
             * IP check
             */
            $this->assertEquals($ip, $actual->getClientAddress());
            $this->assertEquals($ip, $actual->getCountry()->getClientAddress());
            $this->assertEquals($ip, $actual->getRegion()->getClientAddress());
            $this->assertEquals($ip, $actual->getCity()->getClientAddress());

            /*
             * Country check
             */
            if ($expected[$ip]['country']['name'] !== null && strpos($expected[$ip]['country']['name'], '/') === 0) {
                $this->assertRegExp($expected[$ip]['country']['name'], $actualCountry->getName());
            } else {
                $this->assertEquals($expected[$ip]['country']['name'], $actualCountry->getName());
            }

            $this->assertEquals($expected[$ip]['country']['iso'],       $actualCountry->getIso());
            $this->assertEquals($expected[$ip]['country']['latitude'],  $actualCountry->getLatitude());
            $this->assertEquals($expected[$ip]['country']['longitude'], $actualCountry->getLongitude());
            $this->assertEquals($expected[$ip]['country']['timezone'],  $actualCountry->getTimeZone());

            /*
             * Region check
             */
            $methodName = $expected[$ip]['region']['name'] !== null && strpos($expected[$ip]['region']['name'], '/') === 0
                ? 'assertRegExp'
                : 'assertEquals';

            $this->{$methodName}($expected[$ip]['region']['name'],     $actualRegion->getName());
            $this->assertEquals($expected[$ip]['region']['iso'],       $actualRegion->getIso());
            $this->assertEquals($expected[$ip]['region']['latitude'],  $actualRegion->getLatitude());
            $this->assertEquals($expected[$ip]['region']['longitude'], $actualRegion->getLongitude());
            $this->assertEquals($expected[$ip]['region']['timezone'],  $actualRegion->getTimeZone());

            /*
             * City check
             */
            $methodName = $expected[$ip]['city']['name'] !== null && strpos($expected[$ip]['city']['name'], '/') === 0
                ? 'assertRegExp'
                : 'assertEquals';

            $this->{$methodName}($expected[$ip]['city']['name'],      $actualCity->getName());
            $this->assertEquals($expected[$ip]['city']['iso'],        $actualCity->getIso());
            $this->assertEquals($expected[$ip]['city']['latitude'],   $actualCity->getLatitude());
            $this->assertEquals($expected[$ip]['city']['longitude'],  $actualCity->getLongitude());
            $this->assertEquals($expected[$ip]['city']['timezone'],   $actualCity->getTimeZone());
        }
    }
}

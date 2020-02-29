<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\MaxMind;

class ProvideAllBatchTest extends TestCase
{
    use BatchProvider;

    /**
     * @dataProvider batchProvider
     */
    public function testProvidingAllDataInBatchMode(array $IPs, array $expected)
    {
        $adapter = new MaxMind(PATH_TO_MAXMIND_BASE);
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
            $this->assertEquals($ip, $actual->getClientAddress(), 'GeoData | Client address asserting');
            $this->assertEquals($ip, $actual->getCountry()->getClientAddress(), 'Country | Client address asserting');
            $this->assertEquals($ip, $actual->getRegion()->getClientAddress(), 'Region | Client address asserting');
            $this->assertEquals($ip, $actual->getCity()->getClientAddress(), 'City | Client address asserting');

            /*
             * Country check
             */
            $methodName = $expected[$ip]['country']['name'] !== null && strpos($expected[$ip]['country']['name'], '/') === 0
                ? 'assertRegExp'
                : 'assertEquals';

            $this->{$methodName}($expected[$ip]['country']['name'],     $actualCountry->getName(), 'Country | Name asserting');
            $this->assertEquals($expected[$ip]['country']['iso'],       $actualCountry->getIso(), 'Country | ISO asserting');
            $this->assertEquals($expected[$ip]['country']['latitude'],  $actualCountry->getLatitude(), 'Country | Latitude asserting');
            $this->assertEquals($expected[$ip]['country']['longitude'], $actualCountry->getLongitude(), 'Country | Longitude asserting');
            $this->assertEquals($expected[$ip]['country']['timezone'],  $actualCountry->getTimeZone(), 'Country | TimeZone asserting');

            /*
             * Region check
             */
            $methodName = $expected[$ip]['region']['name'] !== null && strpos($expected[$ip]['region']['name'], '/') === 0
                ? 'assertRegExp'
                : 'assertEquals';

            $this->{$methodName}($expected[$ip]['region']['name'],     $actualRegion->getName(), 'Region | Name asserting');
            $this->assertEquals($expected[$ip]['region']['iso'],       $actualRegion->getIso(), 'Region | ISO asserting');
            $this->assertEquals($expected[$ip]['region']['latitude'],  $actualRegion->getLatitude(), 'Region | Latitude asserting');
            $this->assertEquals($expected[$ip]['region']['longitude'], $actualRegion->getLongitude(), 'Region | Longitude asserting');
            $this->assertEquals($expected[$ip]['region']['timezone'],  $actualRegion->getTimeZone(), 'Region | TimeZone asserting');

            /*
             * City check
             */
            $methodName = $expected[$ip]['city']['name'] !== null && strpos($expected[$ip]['city']['name'], '/') === 0
                ? 'assertRegExp'
                : 'assertEquals';

            $this->{$methodName}($expected[$ip]['city']['name'],      $actualCity->getName(), 'City | Name asserting');
            $this->assertEquals($expected[$ip]['city']['iso'],        $actualCity->getIso(), 'City | ISO asserting');
            $this->assertEquals($expected[$ip]['city']['latitude'],   $actualCity->getLatitude(), 'City | Latitude asserting');
            $this->assertEquals($expected[$ip]['city']['longitude'],  $actualCity->getLongitude(), 'City | Longitude asserting');
            $this->assertEquals($expected[$ip]['city']['timezone'],   $actualCity->getTimeZone(), 'City | TimeZone asserting');
        }
    }
}

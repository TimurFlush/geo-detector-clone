<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\MaxMind;

class ProvideAllTest extends TestCase
{
    use ClientAddressProvider;

    /**
     * @dataProvider clientAddressProvider
     */
    public function testProvidingAllData(string $ip, array $expected)
    {
        $adapter  = new MaxMind(PATH_TO_MAXMIND_BASE);
        $actual   = $adapter->provideAll($ip);

        $actualCountry = $actual->getCountry();
        $actualRegion = $actual->getRegion();
        $actualCity = $actual->getCity();

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
        $methodName = $expected['country']['name'] !== null && strpos($expected['country']['name'], '/') === 0
            ? 'assertRegExp'
            : 'assertEquals';

        $this->{$methodName}($expected['country']['name'],     $actualCountry->getName(), 'Country | Name asserting');
        $this->assertEquals($expected['country']['iso'],       $actualCountry->getIso(), 'Country | ISO asserting');
        $this->assertEquals($expected['country']['latitude'],  $actualCountry->getLatitude(), 'Country | Latitude asserting');
        $this->assertEquals($expected['country']['longitude'], $actualCountry->getLongitude(), 'Country | Longitude asserting');
        $this->assertEquals($expected['country']['timezone'],  $actualCountry->getTimeZone(), 'Country | TimeZone asserting');

        /*
         * Region check
         */
        $methodName = $expected['region']['name'] !== null && strpos($expected['region']['name'], '/') === 0
            ? 'assertRegExp'
            : 'assertEquals';

        $this->{$methodName}($expected['region']['name'],     $actualRegion->getName(), 'Region | Name asserting');
        $this->assertEquals($expected['region']['iso'],       $actualRegion->getIso(), 'Region | ISO asserting');
        $this->assertEquals($expected['region']['latitude'],  $actualRegion->getLatitude(), 'Region | Latitude asserting');
        $this->assertEquals($expected['region']['longitude'], $actualRegion->getLongitude(), 'Region | Longitude asserting');
        $this->assertEquals($expected['region']['timezone'],  $actualRegion->getTimeZone(), 'Region | TimeZone asserting');

        /*
         * City check
         */
        $methodName = $expected['city']['name'] !== null && strpos($expected['city']['name'], '/') === 0
            ? 'assertRegExp'
            : 'assertEquals';

        $this->{$methodName}($expected['city']['name'],      $actualCity->getName(), 'City | Name asserting');
        $this->assertEquals($expected['city']['iso'],        $actualCity->getIso(), 'City | ISO asserting');
        $this->assertEquals($expected['city']['latitude'],   $actualCity->getLatitude(), 'City | Latitude asserting');
        $this->assertEquals($expected['city']['longitude'],  $actualCity->getLongitude(), 'City | Longitude asserting');
        $this->assertEquals($expected['city']['timezone'],   $actualCity->getTimeZone(), 'City | TimeZone asserting');
    }
}

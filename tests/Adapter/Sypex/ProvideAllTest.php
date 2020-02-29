<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\Sypex;

class ProvideAllTest extends TestCase
{
    use ClientAddressProvider;

    /**
     * @dataProvider clientAddressProvider
     */
    public function testProvidingAllData(string $ip, array $expected)
    {
        $adapter  = new Sypex(PATH_TO_SYPEX_BASE);
        $actual   = $adapter->provideAll($ip);

        $actualCountry = $actual->getCountry();
        $actualRegion = $actual->getRegion();
        $actualCity = $actual->getCity();

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
        if ($expected['country']['name'] !== null && strpos($expected['country']['name'], '/') === 0) {
            $this->assertRegExp($expected['country']['name'], $actualCountry->getName());
        } else {
            $this->assertEquals($expected['country']['name'], $actualCountry->getName());
        }

        $this->assertEquals($expected['country']['iso'],       $actualCountry->getIso());
        $this->assertEquals($expected['country']['latitude'],  $actualCountry->getLatitude());
        $this->assertEquals($expected['country']['longitude'], $actualCountry->getLongitude());
        $this->assertEquals($expected['country']['timezone'],  $actualCountry->getTimeZone());

        /*
         * Region check
         */
        $methodName = $expected['region']['name'] !== null && strpos($expected['region']['name'], '/') === 0
            ? 'assertRegExp'
            : 'assertEquals';

        $this->{$methodName}($expected['region']['name'],     $actualRegion->getName());
        $this->assertEquals($expected['region']['iso'],       $actualRegion->getIso());
        $this->assertEquals($expected['region']['latitude'],  $actualRegion->getLatitude());
        $this->assertEquals($expected['region']['longitude'], $actualRegion->getLongitude());
        $this->assertEquals($expected['region']['timezone'],  $actualRegion->getTimeZone());

        /*
         * City check
         */
        $methodName = $expected['city']['name'] !== null && strpos($expected['city']['name'], '/') === 0
            ? 'assertRegExp'
            : 'assertEquals';

        $this->{$methodName}($expected['city']['name'],      $actualCity->getName());
        $this->assertEquals($expected['city']['iso'],        $actualCity->getIso());
        $this->assertEquals($expected['city']['latitude'],   $actualCity->getLatitude());
        $this->assertEquals($expected['city']['longitude'],  $actualCity->getLongitude());
        $this->assertEquals($expected['city']['timezone'],   $actualCity->getTimeZone());
    }
}

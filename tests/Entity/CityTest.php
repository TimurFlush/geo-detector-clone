<?php

namespace TimurFlush\GeoDetector\Tests\Entity;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\City;

class CityTest extends TestCase
{
    public function testRetrievingData()
    {
        $city = new City();

        /*
         * Check null by-default
         */
        $this->assertNull($city->getClientAddress());
        $this->assertNull($city->getName());
        $this->assertNull($city->getIso());
        $this->assertNull($city->getLatitude());
        $this->assertNull($city->getLongitude());
        $this->assertNull($city->getTimeZone());

        /*
         * WITH toJson()
         */
        $expected = json_encode(
            [
                'clientAddress' => null,
                'name' => null,
                'iso' => null,
                'latitude' => null,
                'longitude' => null,
                'timeZone' => null,
            ]
        );
        $this->assertJsonStringEqualsJsonString($expected, $city->toJson());

        /*
         * Check filling
         */
        $city->setClientAddress($cA = '127.0.0.3');
        $city->setName($nM = 'Russia');
        $city->setIso($iso = 'RU');
        $city->setLatitude($lat = '50');
        $city->setLongitude($lon = '40');
        $city->setTimeZone($tz = 'Asia/Vladivostok');

        $this->assertEquals($cA, $city->getClientAddress());
        $this->assertEquals($nM, $city->getName());
        $this->assertEquals($iso, $city->getIso());
        $this->assertEquals($lat, $city->getLatitude());
        $this->assertEquals($lon, $city->getLongitude());
        $this->assertEquals($tz, $city->getTimeZone());

        /*
         * WITH toJson()
         */
        $expected = json_encode(
            [
                'clientAddress' => $cA,
                'name' => $nM,
                'iso' => $iso,
                'latitude' => $lat,
                'longitude' => $lon,
                'timeZone' => $tz,
            ]
        );

        $this->assertJsonStringEqualsJsonString($expected, $city->toJson());
    }
}

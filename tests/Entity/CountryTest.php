<?php

namespace TimurFlush\GeoDetector\Tests\Entity;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\Country;

class CountryTest extends TestCase
{
    public function testRetrievingData()
    {
        $country = new Country();

        /*
         * Check null by-default
         */
        $this->assertNull($country->getClientAddress());
        $this->assertNull($country->getName());
        $this->assertNull($country->getIso());
        $this->assertNull($country->getLatitude());
        $this->assertNull($country->getLongitude());
        $this->assertNull($country->getTimeZone());

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
        $this->assertJsonStringEqualsJsonString($expected, $country->toJson());

        /*
         * Check filling
         */
        $country->setClientAddress($cA = '127.0.0.3');
        $country->setName($nM = 'Russia');
        $country->setIso($iso = 'RU');
        $country->setLatitude($lat = '50');
        $country->setLongitude($lon = '40');
        $country->setTimeZone($tz = 'Asia/Yekaterinburg');

        $this->assertEquals($cA, $country->getClientAddress());
        $this->assertEquals($nM, $country->getName());
        $this->assertEquals($iso, $country->getIso());
        $this->assertEquals($lat, $country->getLatitude());
        $this->assertEquals($lon, $country->getLongitude());
        $this->assertEquals($tz, $country->getTimeZone());

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
                'timeZone' => $tz
            ]
        );

        $this->assertJsonStringEqualsJsonString($expected, $country->toJson());
    }
}

<?php

namespace TimurFlush\GeoDetector\Tests\Entity;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\Region;

class RegionTest extends TestCase
{
    public function testRetrievingData()
    {
        $region = new Region();

        /*
         * Check null by-default
         */
        $this->assertNull($region->getClientAddress());
        $this->assertNull($region->getName());
        $this->assertNull($region->getIso());
        $this->assertNull($region->getLatitude());
        $this->assertNull($region->getLongitude());
        $this->assertNull($region->getTimeZone());

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
        $this->assertJsonStringEqualsJsonString($expected, $region->toJson());

        /*
         * Check filling
         */
        $region->setClientAddress($cA = '127.0.0.3');
        $region->setName($nM = 'Russia');
        $region->setIso($iso = 'RU');
        $region->setLatitude($lat = '50');
        $region->setLongitude($lon = '40');
        $region->setTimeZone($tz = 'Europe/Moscow');

        $this->assertEquals($cA, $region->getClientAddress());
        $this->assertEquals($nM, $region->getName());
        $this->assertEquals($iso, $region->getIso());
        $this->assertEquals($lat, $region->getLatitude());
        $this->assertEquals($lon, $region->getLongitude());
        $this->assertEquals($tz, $region->getTimeZone());

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

        $this->assertJsonStringEqualsJsonString($expected, $region->toJson());
    }
}

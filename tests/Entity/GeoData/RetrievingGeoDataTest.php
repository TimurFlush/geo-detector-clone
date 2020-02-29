<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Entity\Region;
use TimurFlush\GeoDetector\Tests\Adapter\RandomTorIP;

class RetrievingGeoDataTest extends TestCase
{
    use RandomTorIP;

    public function testRetrievingGeoData()
    {
        $country = (new Country())->setClientAddress($cA = '127.0.0.1')->setName('Russia');
        $region = (new Region())->setClientAddress($cA)->setName('Primorskij krai');
        $city = (new City())->setClientAddress($cA)->setName('Vladivostok');

        $entity = new GeoData($cA);

        $this->assertEquals((new Country())->setClientAddress($cA), $entity->getCountry(), 'If country is not defined');
        $this->assertEquals((new Region())->setClientAddress($cA), $entity->getRegion(), 'If region is not defined');
        $this->assertEquals((new City())->setClientAddress($cA), $entity->getCity(), 'If city is not defined');

        /*
         * Check retrieving address via __construct
         */
        $entity = new GeoData($cA = '127.0.0.2');

        $this->assertEquals($cA, $entity->getClientAddress());

        $entity->setCountry($country);
        $entity->setRegion($region);
        $entity->setCity($city);
        $entity->setClientAddress($cA = '127.0.0.3');
        $entity->setTorStatus($tor = true);

        $this->assertEquals($country, $entity->getCountry());
        $this->assertEquals($region, $entity->getRegion());
        $this->assertEquals($city, $entity->getCity());
        $this->assertEquals($cA, $entity->getClientAddress());
        $this->assertEquals($tor, $entity->getTorStatus());

        $entity->setTorStatus($tor = false);
        $this->assertEquals($tor, $entity->getTorStatus());
    }

    public function testRetrievingTorStatus()
    {
        $ipList = [
            '127.0.0.1'    => false,
            '127.0.0.3'    => false,
            '51.102.95.13' => false,
            $this->getRandomTorIP() => true,
            $this->getRandomTorIP() => true,
            $this->getRandomTorIP() => true,
        ];

        foreach ($ipList as $ip => $torStatus) {
            $geoData = new GeoData($ip);
            $this->assertEquals(
                $exp = $torStatus,
                $act = $geoData->getTorStatus(),
                sprintf(
                    'Retrieving tor status | %s | EXP: %s | ACT: %s',
                    $ip, ($exp ? 'TRUE' : 'FALSE'), ($act ? 'TRUE' : 'FALSE')
                )
            );
        }
    }
}

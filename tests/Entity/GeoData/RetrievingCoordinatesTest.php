<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\GeoData;

class RetrievingCoordinatesTest extends TestCase
{
    use CoordinatesProvider;
    use OptionsCreator;

    /**
     * @dataProvider coordinatesProvider
     */
    public function testRetrievingCoordinates($clientAddress, $country, $region, $city, $expLat, $extLon)
    {
        $options = $this->createOptionsFromProvidedData($country, $region, $city);

        $entity = new GeoData($clientAddress, $options);

        $this->assertEquals($expLat, $entity->getLatitude());
        $this->assertEquals($extLon, $entity->getLongitude());
    }
}

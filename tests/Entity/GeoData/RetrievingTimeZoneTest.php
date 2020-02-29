<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\GeoData;

class RetrievingTimeZoneTest extends TestCase
{
    use TimeZoneProvider;
    use OptionsCreator;

    /**
     * @dataProvider timeZoneProvider
     */
    public function testRetrievingTimeZone($clientAddress, $country, $region, $city, $expTZ)
    {
        $options = $this->createOptionsFromProvidedData($country, $region, $city);

        $entity = new GeoData($clientAddress, $options);

        $this->assertEquals($expTZ, $entity->getTimeZone());
    }
}

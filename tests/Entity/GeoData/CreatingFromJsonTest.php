<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\GeoData;

class CreatingFromJsonTest extends TestCase
{
    use OptionsProvider;
    use OptionsCreator;

    /**
     * @dataProvider optionsProvider
     */
    public function testCreatingFromJson($clientAddress, ...$options)
    {
        $expectedOptions  = $this->createOptionsFromProvidedData(...$options) + ['clientAddress' => $clientAddress];

        $actualObject = GeoData::createFromJson(json_encode($expectedOptions));

        $this->assertEquals($clientAddress, $actualObject->getClientAddress());
        $this->assertEquals($expectedOptions['country'], $actualObject->getCountry());
        $this->assertEquals($expectedOptions['region'], $actualObject->getRegion());
        $this->assertEquals($expectedOptions['city'], $actualObject->getCity());
        $this->assertEquals($expectedOptions['torStatus'], $actualObject->getTorStatus());
    }
}

<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\GeoData;
use ReflectionObject;
use TimurFlush\GeoDetector\Entity\Region;

class FillingViaConstructorTest extends TestCase
{
    use OptionsProvider;
    use OptionsCreator;

    /**
     * @dataProvider optionsProvider
     *
     * @param string $expectedClientAddress
     * @param        $options
     */
    public function testGeoDataFilling($expectedClientAddress, ...$options)
    {
        $expectedOptions       = $this->createOptionsFromProvidedData(...$options);

        ksort($expectedOptions);

        $geoData = new GeoData($expectedClientAddress, $expectedOptions);

        $actualOptions = [];
        $actualClientAddress = null;

        $refl = new ReflectionObject($geoData);
        foreach ($refl->getProperties() as $property) {
            if ($property->isPrivate() || $property->isProtected()) {
                /*
                 * Access to private and protected properties.
                 */
                $property->setAccessible(true);
            }

            /*
             * Separate checks
             */
            if ($property->getName() !== 'clientAddress') {
                $actualOptions[$property->getName()] = $property->getValue($geoData);
            } else {
                $actualClientAddress = $property->getValue($geoData);
            }
        }

        ksort($actualOptions);

        /*
         * Assert client addresses
         */
        $this->assertEquals($expectedClientAddress, $actualClientAddress);

        /*
         * Assert options
         */
        $this->assertEquals($expectedOptions, $actualOptions);
    }
}

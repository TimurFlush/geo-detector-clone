<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\EntityAbstract;
use TimurFlush\GeoDetector\Entity\GeoData;
use ReflectionObject;

class ConvertingToJsonTest extends TestCase
{
    use OptionsProvider;
    use OptionsCreator;

    /**
     * @dataProvider optionsProvider
     */
    public function testConvertingToJson($clientAddress, ...$options)
    {
        $options = $this->createOptionsFromProvidedData(...$options);

        $entity       = new GeoData($clientAddress, $options);
        $actualJson   = $entity->toJson();

        $expectedProperties = [];

        foreach ((new ReflectionObject($entity))->getProperties() as $property) {
            /*
             * Access via getter
             */
            $value = $entity->{'get' . ucfirst($name = $property->getName())}();

            /*
             * Expected value depends on instanceof EntityAbstract
             */
            $expectedProperties[$name] = $value instanceof EntityAbstract
                ? $value->toJson()
                : $value;
        }

        $expectedJson = json_encode($expectedProperties);

        $this->assertJsonStringEqualsJsonString($expectedJson, $actualJson);
    }
}

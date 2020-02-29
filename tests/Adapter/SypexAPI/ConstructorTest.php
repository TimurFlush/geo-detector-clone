<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\SypexAPI;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\SypexAPI;

class ConstructorTest extends TestCase
{
    protected function getPropertyValues(SypexAPI $sypex): array
    {
        $refl = new \ReflectionObject($sypex);

        $serverProperty = $refl->getProperty('server');
        $serverProperty->setAccessible(true);

        $licenseKeyProperty = $refl->getProperty('licenseKey');
        $licenseKeyProperty->setAccessible(true);

        return [
            'server'     => $serverProperty->getValue($sypex),
            'licenseKey' => $licenseKeyProperty->getValue($sypex)
        ];
    }

    public function testDefaultPropertyValues()
    {
        $refl    = new \ReflectionClass(SypexAPI::class);
        $adapter = $refl->newInstanceWithoutConstructor();

        $serverProperty = $refl->getProperty('server');
        $serverProperty->setAccessible(true);

        $licenseKeyProperty = $refl->getProperty('licenseKey');
        $licenseKeyProperty->setAccessible(true);

        /*
         * We need to know that these properties are null
         */
        $this->assertNull($serverProperty->getValue($adapter));
        $this->assertNull($licenseKeyProperty->getValue($adapter));
    }

    public function testConstructorDefaultBehavior()
    {
        $adapter = new SypexAPI();
        $actualProperties = $this->getPropertyValues($adapter);

        $this->assertEquals('api.sypexgeo.net', $actualProperties['server']);
        $this->assertNull($actualProperties['licenseKey']);
    }

    public function testPassingDataViaConstructor()
    {
        /*
         * Check default constructor behavior.
         */
        $adapter = new SypexAPI(
            $expectedServer  = 'some.server.com',
            $expectedLicense = 'someLicense'
        );
        $actualProperties = $this->getPropertyValues($adapter);

        $this->assertEquals($expectedServer, $actualProperties['server']);
        $this->assertEquals($expectedLicense, $actualProperties['licenseKey']);
    }
}

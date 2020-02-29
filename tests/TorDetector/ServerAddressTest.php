<?php

namespace TimurFlush\GeoDetector\Tests\TorDetector;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Exception;
use TimurFlush\GeoDetector\TorDetector;

class ServerAddressTest extends TestCase
{
    public function setUp(): void
    {
        $refl = new \ReflectionClass(TorDetector::class);
        $serverAddressProperty = $refl->getProperty('serverAddress');

        $serverAddressProperty->setAccessible(true);
        $serverAddressProperty->setValue(null);
    }

    public function tearDown(): void
    {
        $refl = new \ReflectionClass(TorDetector::class);
        $serverAddressProperty = $refl->getProperty('serverAddress');

        $serverAddressProperty->setAccessible(true);
        $serverAddressProperty->setValue(MACHINE_IP);
    }

    public function testThrowingExceptionIfUnableToResolveTheServerAddress()
    {
        $this->expectExceptionObject(
            new Exception('Unable to resolve the server IP address. Please set it explicitly via ' . TorDetector::class . '::serverAddress()')
        );

        $refl = new \ReflectionClass(TorDetector::class);
        $serverAddressProperty = $refl->getProperty('serverAddress');

        $serverAddressProperty->setAccessible(true);
        $serverAddressProperty->setValue(null);

        TorDetector::serverAddress();
    }

    public function testRetrievingServerAddress()
    {
        $expectedAddress = '192.168.0.1';

        TorDetector::serverAddress($expectedAddress);

        $this->assertEquals($expectedAddress, TorDetector::serverAddress());
    }

    public function testRetrievingServerAddressFromSERVER()
    {
        $expectedAddress = $_SERVER['SERVER_ADDR'] = '192.168.0.1';

        $this->assertEquals($expectedAddress, TorDetector::serverAddress());
    }

    public function testThrowingExceptionIfPassedInvalidIPV4()
    {
        $invalidAddress = 'invalid-ipv4';

        $this->expectExceptionObject(
            new Exception('Passed the invalid ipv4: ' . $invalidAddress)
        );

        TorDetector::serverAddress($invalidAddress);
    }

    public function testResettingState()
    {
        TorDetector::serverAddress('127.0.0.1');
        TorDetector::resetState();

        $refl     = new \ReflectionClass(TorDetector::class);
        $property = $refl->getProperty('serverAddress');
        $property->setAccessible(true);

        $this->assertNull($property->getValue());
    }
}

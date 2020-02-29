<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\SypexAPI;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Adapter\SypexAPI;

class ThrowingExceptionsTest extends TestCase
{
    public function testThrowingExceptionsWithInvalidLicenseKey()
    {
        $adapter = new SypexAPI(null, '44515');

        $this->expectExceptionMessageMatches("/The '.*' say: Указанный ключ не найден/i");

        $adapter->provideAll('127.0.0.1');
    }

    public function testThrowingExceptionWithIncorrectLicenseKey()
    {
        $adapter = new SypexAPI(null, 'incorrect-license-key');

        $this->expectExceptionMessageMatches("/The '.*' returned the wrong response./i");

        $adapter->provideAll('127.0.0.1');
    }
}

<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use PHPUnit\Framework\TestCase;
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Exception;

class ThrowingExceptionTest extends TestCase
{
    public function testThrowingExceptionOnCreateFromJsonCaseInvalidJson()
    {
        /*
         * If invalid json
         */
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Unable to parse json. Message: .*/');

        GeoData::createFromJson('iNv-!aLiD-JSON');
    }

    public function testThrowingExceptionOnCreateFromJsonCaseIpMissing()
    {
        /*
         * If clientAddress is missing
         */
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Provided JSON does not contain \'clientAddress\' attribute./');

        GeoData::createFromJson('{}');
    }
}

<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\Region;

trait TimeZoneProvider
{
    public function timeZoneProvider()
    {
        return [
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU')
                    ->setTimeZone('TZ1'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Primorskij krai')
                    ->setIso('RU-PRI')
                    ->setTimeZone('TZ2'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Vladivostok')
                    ->setIso('VVO')
                    ->setTimeZone('TZ3'),
                'TZ3',
            ],
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU')
                    ->setTimeZone('TZ1'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('RU-MOW')
                    ->setTimeZone('TZ2'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('MOW'),
                'TZ2',
            ],
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU')
                    ->setTimeZone('TZ1'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('RU-MOW'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('MOW'),
                'TZ1'
            ],
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('RU-MOW'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('MOW'),
                null,
            ]
        ];
    }
}

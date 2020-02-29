<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\Region;

trait OptionsProvider
{
    /**
     * @return array
     */
    public function optionsProvider()
    {
        return [
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU')
                    ->setLatitude('100')
                    ->setLongitude('200'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Primorskij krai')
                    ->setIso('RU-PRI')
                    ->setLatitude('200')
                    ->setLongitude('300'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Vladivostok')
                    ->setIso('VVO')
                    ->setLatitude('300')
                    ->setLongitude('400'),
                true,
            ],
            [
                $cA = '127.0.0.1',
                (new Country())
                    ->setClientAddress($cA)
                    ->setName('Russian Federation')
                    ->setIso('RU')
                    ->setLatitude('100')
                    ->setLongitude('200'),
                (new Region())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('RU-MOW')
                    ->setLatitude('200')
                    ->setLatitude('300'),
                (new City())
                    ->setClientAddress($cA)
                    ->setName('Moscow')
                    ->setIso('MOW')
                    ->setLatitude('300')
                    ->setLatitude('400'),
                true,
            ],
        ];
    }
}

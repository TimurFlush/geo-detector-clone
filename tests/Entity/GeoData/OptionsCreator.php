<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Entity\GeoData;

use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\Region;

trait OptionsCreator
{
    /**
     * @param $country
     * @param $region
     * @param $city
     * @param $tor
     *
     * @return array
     */
    protected function createOptionsFromProvidedData($country, $region, $city, bool $tor = false): array
    {
        return [
            'country'   => $country,
            'region'    => $region,
            'city'      => $city,
            'torStatus' => $tor,
        ];
    }
}

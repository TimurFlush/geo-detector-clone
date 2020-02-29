<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Adapter\Sypex;

use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Exception;
use JsonMapper_Exception;
use TimurFlush\GeoDetector\TorDetector;

trait InteractsWithGeoData
{
    /**
     * Create a geo data entity from an API description.
     *
     * @param string $clientAddress
     * @param array  $description
     *
     * @return GeoData
     *
     * @throws Exception                         Please see the method 'TimurFlush\GeoDetector\Entity\GeoData::__construct()'
     * @throws JsonMapper_Exception              Please see the method 'TimurFlush\GeoDetector\Entity\GeoData::__construct()'
     * @throws \TimurFlush\GeoDetector\Exception Please see the `TorDetector::check()` method.
     */
    protected function createGeoDataFromDescription(string $clientAddress, array $description): GeoData
    {
        // @codingStandardsIgnoreStart
        $map = [
            'clientAddress' => $clientAddress,
            'country' => [
                'clientAddress' => $clientAddress,
                'name'      => isset($description['country']['name_en'])  ? $description['country']['name_en'] : null,
                'iso'       => isset($description['country']['iso'])      ? $description['country']['iso'] : null,
                'latitude'  => isset($description['country']['lat'])      ? $description['country']['lat'] : null,
                'longitude' => isset($description['country']['lon'])      ? $description['country']['lon'] : null,
                'timeZone'  => isset($description['country']['timezone']) ? $description['country']['timezone'] : null,
            ],
            'region' => [
                'clientAddress' => $clientAddress,
                'name'      => isset($description['region']['name_en'])  ? $description['region']['name_en'] : null,
                'iso'       => isset($description['region']['iso'])      ? $description['region']['iso'] : null,
                'latitude'  => isset($description['region']['lat'])      ? $description['region']['lat'] : null,
                'longitude' => isset($description['region']['lon'])      ? $description['region']['lon'] : null,
                'timeZone'  => isset($description['region']['timezone']) ? $description['region']['timezone'] : null,
            ],
            'city' => [
                'clientAddress' => $clientAddress,
                'name'      => isset($description['city']['name_en'])  ? $description['city']['name_en'] : null,
                'iso'       => isset($description['city']['iso'])      ? $description['city']['iso'] : null,
                'latitude'  => isset($description['city']['lat'])      ? $description['city']['lat'] : null,
                'longitude' => isset($description['city']['lon'])      ? $description['city']['lon'] : null,
                'timeZone'  => isset($description['city']['timezone']) ? $description['city']['timezone'] : null,
            ]
        ];
        // @codingStandardsIgnoreEnd

        return GeoData::createFromJson(json_encode($map));
    }
}

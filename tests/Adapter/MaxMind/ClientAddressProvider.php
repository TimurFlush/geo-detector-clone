<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

trait ClientAddressProvider
{
    /**
     * @return array
     */
    public function clientAddressProvider()
    {
        return [
            [
                'ip'       => '176.57.72.105',
                'expected' => [
                    'country' => [
                        'name'      => '/Russia/i',
                        'iso'       => 'RU',
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'region' => [
                        'name'      => '/Moscow/i',
                        'iso'       => 'MOW',
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'city' => [
                        'name'      => '/Moscow/i',
                        'iso'       => null,
                        'latitude'  => '55.7527',
                        'longitude' => '37.6172',
                        'timezone'  => 'Europe/Moscow',
                    ]
                ]
            ],
            [
                'ip'       => '195.234.215.81',
                'expected' => [
                    'country' => [
                        'name'      => 'Ukraine',
                        'iso'       => 'UA',
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'region' => [
                        'name'      => null,
                        'iso'       => null,
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'city' => [
                        'name'      => null,
                        'iso'       => null,
                        'latitude'  => '50.4522',
                        'longitude' => '30.5287',
                        'timezone'  => 'Europe/Kiev',
                    ]
                ]
            ],
            [
                'ip'       => '127.0.0.1',
                'expected' => [
                    'country' => [
                        'name'      => null,
                        'iso'       => null,
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'region' => [
                        'name'      => null,
                        'iso'       => null,
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ],
                    'city' => [
                        'name'      => null,
                        'iso'       => null,
                        'latitude'  => null,
                        'longitude' => null,
                        'timezone'  => null,
                    ]
                ]
            ]
        ];
    }
}

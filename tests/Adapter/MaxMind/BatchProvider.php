<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\MaxMind;

trait BatchProvider
{
    /**
     * @return array
     */
    public function batchProvider()
    {
        return [
            [
                'ip'       => ['176.57.72.105', '195.234.215.81', '127.0.0.1'],
                'expected' => [
                    '176.57.72.105' => [
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
                    ],
                    '195.234.215.81' => [
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
                    ],
                    '127.0.0.1' => [
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
            ]
        ];
    }
}

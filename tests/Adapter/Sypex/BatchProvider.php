<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex;

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
                            'latitude'  => '60',
                            'longitude' => '100',
                            'timezone'  => null,
                        ],
                        'region' => [
                            'name'      => '/Moskovskaya Oblast/i',
                            'iso'       => 'RU-MOS',
                            'latitude'  => null,
                            'longitude' => null,
                            'timezone'  => null,
                        ],
                        'city' => [
                            'name'      => '/Krasnogorsk/i',
                            'iso'       => null,
                            'latitude'  => '55.82036',
                            'longitude' => '37.33017',
                            'timezone'  => null,
                        ]
                    ],
                    '195.234.215.81' => [
                        'country' => [
                            'name'      => 'Ukraine',
                            'iso'       => 'UA',
                            'latitude'  => '49',
                            'longitude' => '32',
                            'timezone'  => null,
                        ],
                        'region' => [
                            'name'      => 'Kyiv',
                            'iso'       => 'UA-30',
                            'latitude'  => null,
                            'longitude' => null,
                            'timezone'  => null,
                        ],
                        'city' => [
                            'name'      => 'Kyiv',
                            'iso'       => null,
                            'latitude'  => '50.45466',
                            'longitude' => '30.5238',
                            'timezone'  => null,
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

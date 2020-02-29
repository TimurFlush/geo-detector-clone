<?php

/**
 * This file is part of the TimurFlush\GeoDetector library.
 *
 * (c) Timur Flush <flush02@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Some parts of this file are part of the program code of Ukrainian
 * company "BINOVATOR" (http://binovator.com/contacts)
 */

namespace TimurFlush\GeoDetector\Adapter;

use Psr\SimpleCache\CacheInterface;
use TimurFlush\GeoDetector\Adapter\Sypex\InteractsWithGeoData;
use TimurFlush\GeoDetector\AdapterInterface;
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Exception;
use TimurFlush\GeoDetector\TorDetector;

class Sypex implements AdapterInterface
{
    use InteractsWithGeoData;

    public const SXGEO_FILE = 0;
    public const SXGEO_MEMORY = 1;
    public const SXGEO_BATCH = 2;
    public const DEFAULT_FILE_NAME = 'sypex-database.dat';

    protected $fh;
    protected $ip1c;
    protected $info;
    protected $range;
    protected $db_begin;
    protected $block_len;
    protected $b_idx_str;
    protected $b_idx_len;
    protected $m_idx_str;
    protected $b_idx_arr;
    protected $m_idx_arr;
    protected $m_idx_len;
    protected $id_len;
    protected $db_items;
    protected $country_size;
    protected $db;
    protected $regions_db;
    protected $cities_db;
    protected $max_region;
    protected $max_city;
    protected $max_country;
    protected $pack;

    public $id2iso = [
        '',   'AP', 'EU', 'AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'CW', 'AO', 'AQ', 'AR', 'AS', 'AT', 'AU',
        'AW', 'AZ', 'BA', 'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BM', 'BN', 'BO', 'BR', 'BS',
        'BT', 'BV', 'BW', 'BY', 'BZ', 'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN',
        'CO', 'CR', 'CU', 'CV', 'CX', 'CY', 'CZ', 'DE', 'DJ', 'DK', 'DM', 'DO', 'DZ', 'EC', 'EE', 'EG',
        'EH', 'ER', 'ES', 'ET', 'FI', 'FJ', 'FK', 'FM', 'FO', 'FR', 'SX', 'GA', 'GB', 'GD', 'GE', 'GF',
        'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'HK', 'HM', 'HN',
        'HR', 'HT', 'HU', 'ID', 'IE', 'IL', 'IN', 'IO', 'IQ', 'IR', 'IS', 'IT', 'JM', 'JO', 'JP', 'KE',
        'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ', 'LA', 'LB', 'LC', 'LI', 'LK', 'LR',
        'LS', 'LT', 'LU', 'LV', 'LY', 'MA', 'MC', 'MD', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP',
        'MQ', 'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ', 'NA', 'NC', 'NE', 'NF', 'NG', 'NI',
        'NL', 'NO', 'NP', 'NR', 'NU', 'NZ', 'OM', 'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PL', 'PM', 'PN',
        'PR', 'PS', 'PT', 'PW', 'PY', 'QA', 'RE', 'RO', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SE', 'SG',
        'SH', 'SI', 'SJ', 'SK', 'SL', 'SM', 'SN', 'SO', 'SR', 'ST', 'SV', 'SY', 'SZ', 'TC', 'TD', 'TF',
        'TG', 'TH', 'TJ', 'TK', 'TM', 'TN', 'TO', 'TL', 'TR', 'TT', 'TV', 'TW', 'TZ', 'UA', 'UG', 'UM',
        'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'YE', 'YT', 'RS', 'ZA',
        'ZM', 'ME', 'ZW', 'A1', 'XK', 'O1', 'AX', 'GG', 'IM', 'JE', 'BL', 'MF', 'BQ', 'SS'
    ];

    public $batch_mode  = false;
    public $memory_mode = false;

    /**
     * SxGeo constructor.
     *
     * @param string|null $dbFile
     * @param int $mode
     *
     * @throws Exception
     * @throws Exception
     *
     * @codeCoverageIgnore
     */
    public function __construct(?string $dbFile = null, $mode = self::SXGEO_FILE)
    {
        if ($dbFile === null) {
            $dbFile = __DIR__ . DIRECTORY_SEPARATOR . static::DEFAULT_FILE_NAME;
        }

        $this->fh = fopen($dbFile, 'rb');
        
        // Сначала убеждаемся, что есть файл базы данных
        $header = fread($this->fh, 40); // В версии 2.2 заголовок увеличился на 8 байт

        if (substr($header, 0, 3) != 'SxG') {
            throw new Exception('Cannot open ' . $dbFile);
        }

        $info = unpack('Cver/Ntime/Ctype/Ccharset/Cb_idx_len/nm_idx_len/nrange/Ndb_items/Cid_len/nmax_region/nmax_city/Nregion_size/Ncity_size/nmax_country/Ncountry_size/npack_size', substr($header, 3));

        if ($info['b_idx_len'] * $info['m_idx_len'] * $info['range'] * $info['db_items'] * $info['time'] * $info['id_len'] == 0) {
            throw new Exception('Wrong file format' . $dbFile);
        }

        $this->range       = $info['range'];
        $this->b_idx_len   = $info['b_idx_len'];
        $this->m_idx_len   = $info['m_idx_len'];
        $this->db_items    = $info['db_items'];
        $this->id_len      = $info['id_len'];
        $this->block_len   = 3 + $this->id_len;
        $this->max_region  = $info['max_region'];
        $this->max_city    = $info['max_city'];
        $this->max_country = $info['max_country'];
        $this->country_size= $info['country_size'];
        $this->batch_mode  = $mode & self::SXGEO_BATCH;
        $this->memory_mode = $mode & self::SXGEO_MEMORY;
        $this->pack        = $info['pack_size'] ? explode("\0", fread($this->fh, $info['pack_size'])) : '';
        $this->b_idx_str   = fread($this->fh, $info['b_idx_len'] * 4);
        $this->m_idx_str   = fread($this->fh, $info['m_idx_len'] * 4);

        $this->db_begin = ftell($this->fh);

        if ($this->batch_mode) {
            $this->b_idx_arr = array_values(unpack("N*", $this->b_idx_str)); // Быстрее в 5 раз, чем с циклом
            unset($this->b_idx_str);

            $this->m_idx_arr = str_split($this->m_idx_str, 4); // Быстрее в 5 раз чем с циклом
            unset($this->m_idx_str);
        }

        if ($this->memory_mode && !$this->db) {
            $this->db  = fread($this->fh, $this->db_items * $this->block_len);
            $this->regions_db = $info['region_size'] > 0 ? fread($this->fh, $info['region_size']) : '';
            $this->cities_db  = $info['city_size'] > 0 ? fread($this->fh, $info['city_size']) : '';
        }

        $this->info = $info;
        $this->info['regions_begin'] = $this->db_begin + $this->db_items * $this->block_len;
        $this->info['cities_begin']  = $this->info['regions_begin'] + $info['region_size'];
    }

    /**
     * {@inheritDoc}
     */
    public function provideCountryCode(string $clientAddress): ?string
    {
        $country = $this->getCountry($clientAddress);

        return is_string($country) && !empty($country)
            ? $country
            : null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception             Please see the `static::createGeoDataFromDescription()` method.
     * @throws \JsonMapper_Exception Please see the `static::createGeoDataFromDescription()` method.
     */
    public function provideAll(string $clientAddress): GeoData
    {
        $description = $this->getCityFull($clientAddress);

        if (!is_array($description)) {
            $description = [];
        }

        return $this->createGeoDataFromDescription($clientAddress, $description);
    }

    /**
     * {@inheritDoc}
     */
    public function provideCountryCodesBatch(array $clientAddresses): array
    {
        $IPs = [];

        foreach ($clientAddresses as $clientAddress) {
            $IPs[$clientAddress] = $this->provideCountryCode($clientAddress);
        }

        return $IPs;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception             Please see the `static::provideAll()` method.
     * @throws \JsonMapper_Exception Please see the `static::provideAll()` method.
     */
    public function provideAllBatch(array $clientAddresses): array
    {
        $IPs = [];

        foreach ($clientAddresses as $clientAddress) {
            $IPs[$clientAddress] = $this->provideAll($clientAddress);
        }

        return $IPs;
    }

    /**
     * @param $ipn
     * @param $min
     * @param $max
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    protected function searchIdx($ipn, $min, $max)
    {
        if ($this->batch_mode) {
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;

                if ($ipn > $this->m_idx_arr[$offset]) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }

            while ($ipn > $this->m_idx_arr[$min] && $min++ < $max) {
                // N O P
            }
        } else {
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;
                if ($ipn > substr($this->m_idx_str, $offset*4, 4)) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }

            while ($ipn > substr($this->m_idx_str, $min*4, 4) && $min++ < $max) {
                // N O P
            }
        }

        return $min;
    }

    /**
     * @param $str
     * @param $ipn
     * @param $min
     * @param $max
     *
     * @return float|int
     *
     * @codeCoverageIgnore
     */
    protected function searchDatabase($str, $ipn, $min, $max)
    {
        if ($max - $min > 1) {
            $ipn = substr($ipn, 1);
            while ($max - $min > 8) {
                $offset = ($min + $max) >> 1;
                if ($ipn > substr($str, $offset * $this->block_len, 3)) {
                    $min = $offset;
                } else {
                    $max = $offset;
                }
            }

            while ($ipn >= substr($str, $min * $this->block_len, 3) && ++$min < $max) {
                // N O P
            }
        } else {
            $min++;
        }

        return hexdec(
            bin2hex(
                substr(
                    $str,
                    $min * $this->block_len - $this->id_len,
                    $this->id_len
                )
            )
        );
    }

    /**
     * @param $ip
     *
     * @return bool|float|int
     *
     * @codeCoverageIgnore
     */
    protected function getNum($ip)
    {
        $ip1n = (int)$ip; // Первый байт

        if ($ip1n == 0 || $ip1n == 10 || $ip1n == 127 || $ip1n >= $this->b_idx_len || false === ($ipn = ip2long($ip))) {
            return false;
        }

        $ipn = pack('N', $ipn);
        $this->ip1c = chr($ip1n);

        // Находим блок данных в индексе первых байт
        if ($this->batch_mode) {
            $blocks = [
                'min' => $this->b_idx_arr[$ip1n-1],
                'max' => $this->b_idx_arr[$ip1n]
            ];
        } else {
            $blocks = unpack("Nmin/Nmax", substr($this->b_idx_str, ($ip1n - 1) * 4, 8));
        }

        if ($blocks['max'] - $blocks['min'] > $this->range) {
            // Ищем блок в основном индексе
            $part = $this->searchIdx($ipn, floor($blocks['min'] / $this->range), floor($blocks['max'] / $this->range)-1);
            // Нашли номер блока в котором нужно искать IP, теперь находим нужный блок в БД
            $min = $part > 0 ? $part * $this->range : 0;
            $max = $part > $this->m_idx_len ? $this->db_items : ($part+1) * $this->range;
            // Нужно проверить чтобы блок не выходил за пределы блока первого байта
            if ($min < $blocks['min']) {
                $min = $blocks['min'];
            }
            if ($max > $blocks['max']) {
                $max = $blocks['max'];
            }
        } else {
            $min = $blocks['min'];
            $max = $blocks['max'];
        }

        $len = $max - $min;

        // Находим нужный диапазон в БД
        if ($this->memory_mode) {
            return $this->searchDatabase($this->db, $ipn, $min, $max);
        } else {
            fseek($this->fh, $this->db_begin + $min * $this->block_len);
            return $this->searchDatabase(fread($this->fh, $len * $this->block_len), $ipn, 0, $len);
        }
    }

    /**
     * @param $seek
     * @param $max
     * @param $type
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function readData($seek, $max, $type)
    {
        $raw = '';

        if ($seek && $max) {
            if ($this->memory_mode) {
                $raw = substr($type == 1 ? $this->regions_db : $this->cities_db, $seek, $max);
            } else {
                fseek($this->fh, $this->info[$type == 1 ? 'regions_begin' : 'cities_begin'] + $seek);
                $raw = fread($this->fh, $max);
            }
        }

        return $this->unpack($this->pack[$type], $raw);
    }

    /**
     * @param $seek
     * @param bool $full
     *
     * @return array|bool
     *
     * @codeCoverageIgnore
     */
    protected function parseCity($seek, $full = false)
    {
        if (!$this->pack) {
            return false;
        }

        $only_country = false;

        if ($seek < $this->country_size) {
            $country = $this->readData($seek, $this->max_country, 0);
            $city = $this->unpack($this->pack[2]);
            $city['lat'] = $country['lat'];
            $city['lon'] = $country['lon'];
            $only_country = true;
        } else {
            $city = $this->readData($seek, $this->max_city, 2);
            $country = [
                'id' => $city['country_id'],
                'iso' => $this->id2iso[$city['country_id']]
            ];
            unset($city['country_id']);
        }

        if ($full) {
            $region = $this->readData($city['region_seek'], $this->max_region, 1);

            if (!$only_country) {
                $country = $this->readData($region['country_seek'], $this->max_country, 0);
            }

            unset($city['region_seek']);
            unset($region['country_seek']);

            return [
                'city' => $city,
                'region' => $region,
                'country' => $country
            ];
        } else {
            unset($city['region_seek']);

            return [
                'city' => $city,
                'country' => [
                    'id' => $country['id'],
                    'iso' => $country['iso']
                ]
            ];
        }
    }

    /**
     * @param $pack
     * @param string $item
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function unpack($pack, $item = '')
    {
        $unpacked = [];
        $empty = empty($item);
        $pack = explode('/', $pack);
        $pos = 0;

        foreach ($pack as $p) {
            list($type, $name) = explode(':', $p);
            $type0 = $type[0];

            if ($empty) {
                $unpacked[$name] = $type0 == 'b' || $type0 == 'c' ? '' : 0;
                continue;
            }

            switch ($type0) {
                case 't':
                case 'T':
                    $l = 1;
                    break;

                case 's':
                case 'n':
                case 'S':
                    $l = 2;
                    break;

                case 'm':
                case 'M':
                    $l = 3;
                    break;

                case 'd':
                    $l = 8;
                    break;

                case 'c':
                    $l = (int)substr($type, 1);
                    break;

                case 'b':
                    $l = strpos($item, "\0", $pos)-$pos;
                    break;

                default:
                    $l = 4;
            }

            $val = substr($item, $pos, $l);

            switch ($type0) {
                case 't':
                    $v = unpack('c', $val);
                    break;

                case 'T':
                    $v = unpack('C', $val);
                    break;

                case 's':
                    $v = unpack('s', $val);
                    break;

                case 'S':
                    $v = unpack('S', $val);
                    break;

                case 'm':
                    $v = unpack('l', $val . (ord($val[2]) >> 7 ? "\xff" : "\0"));
                    break;

                case 'M':
                    $v = unpack('L', $val . "\0");
                    break;

                case 'i':
                    $v = unpack('l', $val);
                    break;

                case 'I':
                    $v = unpack('L', $val);
                    break;

                case 'f':
                    $v = unpack('f', $val);
                    break;

                case 'd':
                    $v = unpack('d', $val);
                    break;

                case 'n':
                    $v = current(unpack('s', $val)) / pow(10, $type[1]);
                    break;

                case 'N':
                    $v = current(unpack('l', $val)) / pow(10, $type[1]);
                    break;

                case 'c':
                    $v = rtrim($val, ' ');
                    break;

                case 'b':
                    $v = $val;
                    $l++;
                    break;
            }

            $pos += $l;
            $unpacked[$name] = is_array($v) ? current($v) : $v;
        }

        return $unpacked;
    }

    /**
     * @param $ip
     *
     * @return array|bool|mixed
     *
     * @codeCoverageIgnore
     */
    protected function get($ip)
    {
        return $this->max_city ? $this->getCity($ip) : $this->getCountry($ip);
    }

    /**
     * @param $ip
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    protected function getCountry($ip)
    {
        if ($this->max_city) {
            $tmp = $this->parseCity($this->getNum($ip));
            return $tmp['country']['iso'];
        }

        return $this->id2iso[$this->getNum($ip)];
    }

    /**
     * @param $ip
     *
     * @return bool|float|int|mixed
     *
     * @codeCoverageIgnore
     */
    protected function getCountryId($ip)
    {
        if ($this->max_city) {
            $tmp = $this->parseCity($this->getNum($ip));
            return $tmp['country']['id'];
        }

        return $this->getNum($ip);
    }

    /**
     * @param $ip
     *
     * @return array|bool
     *
     * @codeCoverageIgnore
     */
    protected function getCity($ip)
    {
        $seek = $this->getNum($ip);
        return $seek ? $this->parseCity($seek) : false;
    }

    /**
     * @param $ip
     *
     * @return array|bool
     *
     * @codeCoverageIgnore
     */
    protected function getCityFull($ip)
    {
        $seek = $this->getNum($ip);
        return $seek ? $this->parseCity($seek, 1) : false;
    }

    /**
     * @return array
     *
     * @codeCoverageIgnore
     */
    protected function about()
    {
        $charset = ['utf-8', 'latin1', 'cp1251'];
        $types = [
            'n/a',
            'SxGeo Country',
            'SxGeo City RU',
            'SxGeo City EN',
            'SxGeo City',
            'SxGeo City Max RU',
            'SxGeo City Max EN',
            'SxGeo City Max'
        ];

        return [
            'Created' => date('Y.m.d', $this->info['time']),
            'Timestamp' => $this->info['time'],
            'Charset' => $charset[$this->info['charset']],
            'Type' => $types[$this->info['type']],
            'Byte Index' => $this->b_idx_len,
            'Main Index' => $this->m_idx_len,
            'Blocks In Index Item' => $this->range,
            'IP Blocks' => $this->db_items,
            'Block Size' => $this->block_len,
            'City' => [
                'Max Length' => $this->max_city,
                'Total Size' => $this->info['city_size'],
            ],
            'Region' => [
                'Max Length' => $this->max_region,
                'Total Size' => $this->info['region_size'],
            ],
            'Country' => [
                'Max Length' => $this->max_country,
                'Total Size' => $this->info['country_size'],
            ]
        ];
    }
}

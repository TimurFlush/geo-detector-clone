<?php

/**
 * This file is part of the TimurFlush\GeoDetector library.
 *
 * (c) Timur Flush <flush02@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Adapter;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use TimurFlush\GeoDetector\AdapterInterface;
use TimurFlush\GeoDetector\Entity\City;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Entity\Region;
use TimurFlush\GeoDetector\TorDetector;
use TimurFlush\GeoDetector\TorDetectorInterface;

class MaxMind implements AdapterInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * MaxMind constructor.
     *
     * @param string $databaseFile
     *
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException Please see the method `GeoIp2\Database\Reader::__construct()`
     */
    public function __construct(string $databaseFile)
    {
        $this->reader = new Reader($databaseFile);
    }

    /**
     * {@inheritDoc}
     */
    public function provideAll(string $clientAddress): GeoData
    {
        try {
            $all = $this->reader->city($clientAddress);
        } catch (AddressNotFoundException $exception) {
            return new GeoData($clientAddress);
        }

        $country = (new Country())
            ->setClientAddress($clientAddress)
            ->setName($all->country->name)
            ->setIso($all->country->isoCode);

        $region = (new Region())
            ->setClientAddress($clientAddress)
            ->setName($all->mostSpecificSubdivision->name)
            ->setIso($all->mostSpecificSubdivision->isoCode);

        $city = (new City())
            ->setClientAddress($clientAddress)
            ->setName($all->city->name)
            ->setLatitude(
                (string)$all->location->latitude
            )
            ->setLongitude(
                (string)$all->location->longitude
            )
            ->setTimeZone($all->location->timeZone);

        $map = [
            'country' => $country,
            'region' => $region,
            'city' => $city,
        ];

        return new GeoData($clientAddress, $map);
    }

    /**
     * {@inheritDoc}
     */
    public function provideCountryCode(string $clientAddress): ?string
    {
        try {
            return $this
                ->reader
                ->city($clientAddress)
                ->country
                ->isoCode;
        } catch (AddressNotFoundException $exception) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
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
}

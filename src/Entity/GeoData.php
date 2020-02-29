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

namespace TimurFlush\GeoDetector\Entity;

use TimurFlush\GeoDetector\Entity\Traits\InteractsWithClientAddress;
use TimurFlush\GeoDetector\Exception;
use JsonException;
use JsonMapper;
use JsonMapper_Exception;
use TimurFlush\GeoDetector\TorDetector;

final class GeoData extends EntityAbstract
{
    use InteractsWithClientAddress;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var Region
     */
    protected $region;

    /**
     * @var City
     */
    protected $city;

    /**
     * @var bool|null
     */
    protected $torStatus = null;

    /**
     * GeoData constructor.
     *
     * @param string $clientAddress
     * @param array  $data
     */
    public function __construct(string $clientAddress, array $data = [])
    {
        $this->clientAddress = $clientAddress;

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Set a country object.
     *
     * @param Country $country
     *
     * @return $this
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get a country object.
     *
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country ?? ($this->country = (new Country())->setClientAddress($this->clientAddress));
    }

    /**
     * Set a region object.
     *
     * @param Region $region
     *
     * @return $this
     */
    public function setRegion(Region $region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Get a region object.
     *
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region ?? ($this->region = (new Region())->setClientAddress($this->clientAddress));
    }

    /**
     * Set a city object.
     *
     * @param City $city
     *
     * @return $this
     */
    public function setCity(City $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get a city object.
     *
     * @return City
     */
    public function getCity(): City
    {
        return $this->city ?? ($this->city = (new City())->setClientAddress($this->clientAddress));
    }

    /**
     * Get a timezone of client address.
     *
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->city->getTimeZone() ??
            $this->region->getTimeZone() ??
            $this->country->getTimeZone();
    }

    /**
     * Sets a tor status.
     *
     * @param bool $status
     *
     * @return bool|null
     */
    public function setTorStatus(bool $status)
    {
        return $this->torStatus = $status;
    }

    /**
     * Returns a tor status.
     *
     * @return bool
     *
     * @throws Exception Please see the `TorDetector::check()` method.
     */
    public function getTorStatus(): bool
    {
        if ($this->torStatus === null) {
            $this->torStatus = TorDetector::check($this->clientAddress);
        }

        return $this->torStatus;
    }

    /**
     * Get a latitude of client address.
     *
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->getCity()->getLatitude() ??
            $this->getRegion()->getLatitude() ??
            $this->getCountry()->getLatitude();
    }

    /**
     * Get a longitude of client address.
     *
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->getCity()->getLongitude() ??
            $this->getRegion()->getLongitude() ??
            $this->getCountry()->getLongitude();
    }

    /**
     * Create a GeoData entity from JSON.
     *
     * @param string $json
     *
     * @return GeoData
     *
     * @throws Exception If unable to parse json.
     * @throws Exception If provided JSON does not contain 'clientAddress' attribute.
     * @throws JsonMapper_Exception
     */
    public static function createFromJson(string $json): GeoData
    {
        try {
            $json = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new Exception('Unable to parse json. Message: ' . $jsonException->getMessage());
        }

        if (!property_exists($json, 'clientAddress')) {
            throw new Exception("Provided JSON does not contain 'clientAddress' attribute.");
        }

        $mapper = new JsonMapper();
        $mapper->bStrictNullTypes = false;

        /** @var GeoData $mapped */
        $mapped = $mapper->map(
            $json,
            new static($json->clientAddress)
        );

        return $mapped;
    }

    /**
     * {@inheritDoc}
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'clientAddress' => $this->clientAddress,
            'country' => $this->getCountry()->toJson(),
            'region' => $this->getRegion()->toJson(),
            'city' => $this->getCity()->toJson(),
            'torStatus' => $this->getTorStatus(),
        ];
    }
}

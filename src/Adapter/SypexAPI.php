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

use TimurFlush\GeoDetector\Adapter\Sypex\InteractsWithGeoData;
use TimurFlush\GeoDetector\AdapterInterface;
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Exception;
use TimurFlush\GeoDetector\Version;
use JsonException;

class SypexAPI implements AdapterInterface
{
    use InteractsWithGeoData;

    /**
     * @var string
     */
    protected string $defaultServer = 'api.sypexgeo.net';

    /**
     * @var string
     */
    protected ?string $server = null;

    /**
     * @var string|null
     */
    protected ?string $licenseKey = null;

    /**
     * SypexAPI constructor.
     *
     * @param string|null $server
     * @param string|null $licenseKey
     */
    public function __construct(?string $server = null, string $licenseKey = null)
    {
        $this->server = $server === null ? $this->defaultServer : $server;
        $this->licenseKey = $licenseKey;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception             Please see the `static::getResponse()` method.
     * @throws Exception             Please see the `TimurFlush\GeoDetector\Entity\GeoData::__construct()` method.
     * @throws \JsonMapper_Exception Please see the `TimurFlush\GeoDetector\Entity\GeoData::__construct()` method.
     */
    public function provideAll(string $clientAddress): GeoData
    {
        return $this->createGeoDataFromDescription($clientAddress, $this->getResponse($clientAddress));
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception Please see the `static::getResponse()` method.
     */
    public function provideCountryCode(string $clientAddress): ?string
    {
        $all = $this->getResponse($clientAddress);

        return isset($all['country']['iso'])
            ? $all['country']['iso']
            : null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception Please see the `static::getResponse()` method.
     */
    public function provideCountryCodesBatch(array $clientAddresses): array
    {
        $all = $this->getResponse($clientAddresses);

        if (!is_array($all[array_key_first($all)])) {
            $all = [$all];
        }

        $IPs = [];

        foreach ($all as $item) {
            $IPs[$item['ip']] = isset($item['country']['iso'])
                ? $item['country']['iso']
                : null;
        }

        return $IPs;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception             Please see the `static::getResponse()` method.
     * @throws Exception             Please see the `static::createGeoDataFromDescription()` method.
     * @throws \JsonMapper_Exception Please see the `static::createGeoDataFromDescription()` method.
     */
    public function provideAllBatch(array $clientAddresses): array
    {
        $all = $this->getResponse($clientAddresses);

        if (!is_array($all[array_key_first($all)])) {
            $all = [$all];
        }

        $IPs = [];

        foreach ($all as $item) {
            $IPs[$item['ip']] = $this->createGeoDataFromDescription(
                $item['ip'],
                $this->getResponse($item['ip'])
            );
        }

        return $IPs;
    }

    /**
     * @param string|array $clientAddress
     *
     * @return array
     *
     * @throws Exception If the clientAddress argument is not a string or an array.
     * @throws Exception If the sypex geo server returned a wrong response.
     * @throws Exception If the sypex geo server returned an invalid json.
     * @throws Exception If the sypex geo server returned a error message.
     */
    protected function getResponse($clientAddress): array
    {
        $segments = [$this->server];

        if (isset($this->licenseKey)) {
            $segments[] = $this->licenseKey;
        }

        $segments[] = 'json';

        if (is_string($clientAddress)) {
            $segments[] = $clientAddress;
        } elseif (is_array($clientAddress)) {
            $segments[] = join(',', $clientAddress);
        } else {
            // @codeCoverageIgnoreStart
            throw new Exception('The clientAddress argument must be a string or an array.');
            // @codeCoverageIgnoreEnd
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://' . join('/', $segments));
        curl_setopt($ch, CURLOPT_USERAGENT, 'TFGeoDetector/' . Version::get());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if (is_bool($response) || $httpCode !== 200) {
            throw new Exception("The '{$this->server}' returned the wrong response.");
        }

        try {
            $decode = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

            if (isset($decode['error']) && !empty($decode['error'])) {
                throw new Exception("The '{$this->server}' say: " . $decode['error']);
            }

            // @codeCoverageIgnoreStart
        } catch (JsonException $exception) {
            throw new Exception("The '{$this->server}' returned an invalid json. Message: " . $exception->getMessage());
            // @codeCoverageIgnoreEnd
        }

        return $decode;
    }
}

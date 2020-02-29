<?php

/**
 * This file is part of the Timur's Flush's geo detector.
 *
 * (c) Timur Flush <flush02@tutanota.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TimurFlush\GeoDetector;

use TimurFlush\GeoDetector\Entity\GeoData;

interface AdapterInterface
{
    /**
     * Provides an ISO country code by client address.
     *
     * @param string $clientAddress
     *
     * @return string|null
     */
    public function provideCountryCode(string $clientAddress): ?string;

    /**
     * Provides a geo-data information by client address.
     *
     * @param string $clientAddress
     *
     * @return GeoData
     */
    public function provideAll(string $clientAddress): GeoData;

    /**
     * Provides a batch of ISO country codes by client addresses.
     *
     * @param array $clientAddresses
     *
     * @return GeoData[]
     */
    public function provideCountryCodesBatch(array $clientAddresses): array;

    /**
     * Provides a batch of geo-data information by client addresses.
     *
     * @param array $clientAddresses
     *
     * @return GeoData[]
     */
    public function provideAllBatch(array $clientAddresses): array;
}

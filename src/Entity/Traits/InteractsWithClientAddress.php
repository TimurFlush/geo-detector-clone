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

namespace TimurFlush\GeoDetector\Entity\Traits;

trait InteractsWithClientAddress
{
    /**
     * @JsonableProperty
     * @var string|null
     */
    protected $clientAddress = null;

    /**
     * @param string $clientAddress
     *
     * @return $this
     */
    public function setClientAddress(string $clientAddress)
    {
        $this->clientAddress = $clientAddress;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientAddress(): ?string
    {
        return $this->clientAddress;
    }
}

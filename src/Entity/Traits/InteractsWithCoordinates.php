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

trait InteractsWithCoordinates
{
    /**
     * @var string|null
     */
    protected ?string $latitude = null;

    /**
     * @var string|null
     */
    protected ?string $longitude = null;

    /**
     * @param string $latitude
     *
     * @return $this
     */
    public function setLatitude(?string $latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $longitude
     *
     * @return $this
     */
    public function setLongitude(?string $longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}

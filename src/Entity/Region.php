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
use TimurFlush\GeoDetector\Entity\Traits\InteractsWithCoordinates;
use TimurFlush\GeoDetector\Entity\Traits\InteractsWithName;
use TimurFlush\GeoDetector\Entity\Traits\InteractsWithTimeZone;

class Region extends EntityAbstract
{
    use InteractsWithClientAddress;
    use InteractsWithName;
    use InteractsWithCoordinates;
    use InteractsWithTimeZone;

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
            'clientAddress' => $this->getClientAddress(),
            'name' => $this->getName(),
            'iso' => $this->getIso(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'timeZone' => $this->getTimeZone(),
        ];
    }
}

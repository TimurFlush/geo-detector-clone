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

trait InteractsWithTimeZone
{
    /**
     * @var string|null
     */
    protected ?string $timeZone = null;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setTimeZone(?string $name)
    {
        $this->timeZone = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }
}

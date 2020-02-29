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

abstract class EntityAbstract implements \JsonSerializable
{
    /**
     * Convert entity to JSON format.
     *
     * @return string
     */
    abstract public function toJson(): string;

    /**
     * @return mixed
     */
    abstract public function jsonSerialize();
}

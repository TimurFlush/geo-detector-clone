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

trait InteractsWithName
{
    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @var string|null
     */
    protected $iso = null;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $iso
     *
     * @return $this
     */
    public function setIso(?string $iso)
    {
        $this->iso = $iso;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIso(): ?string
    {
        return $this->iso;
    }
}

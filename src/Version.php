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

class Version
{
    /**
     * Get a version of this package.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public static function get()
    {
        return '2.0.1';
    }
}

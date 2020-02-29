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

final class State
{
    /**
     * Resets the state.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public static function resetState(): void
    {
        TorDetector::resetState();
    }
}

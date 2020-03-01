<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter;

trait RandomTorIP
{
    function getRandomTorIP()
    {
        static $addresses = [];

        if (!empty($addresses)) {
            return $addresses[random_int(0, sizeof($addresses) - 1)];
        }

        $addresses = (array)file('https://check.torproject.org/exit-addresses');

        foreach ($addresses as $index => $address) {
            if ($pos = strpos($address, 'ExitAddress') !== 0) {
                unset($addresses[$index]);
                continue;
            }

            $addresses[$index] = explode(' ', $address)[1];

            if (sizeof($addresses) === 2) {
                break;
            }
        }

        /*
         * Remove duplicate IPs & Restore keys of the array
         */
        $addresses = array_values(
            array_unique($addresses)
        );

        if (empty($addresses)) {
            throw new \RuntimeException('Unable to parse tor exit nodes.');
        }

        return $addresses[random_int(0, sizeof($addresses) - 1)];
    }
}

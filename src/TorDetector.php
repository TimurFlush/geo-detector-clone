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

/**
 * @author Jeroen Visser
 * @link   https://jrnv.nl/detecting-the-use-of-proxies-and-tor-network-6c240d6cc5f
 */
final class TorDetector
{
    /**
     * @var string|null
     */
    protected static ?string $serverAddress = null;

    /**
     * @param string $serverAddress
     *
     * @throws Exception If the specified ip address is not ipv4.
     * @throws Exception If unable to resolve the server IP address.
     *
     * @return string|void
     */
    public static function serverAddress(string $serverAddress = null)
    {
        if (is_string($serverAddress)) {
            if (!filter_var($serverAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                throw new Exception('Passed the invalid ipv4: ' . $serverAddress);
            }

            static::$serverAddress = $serverAddress;
        } else {
            $serverAddress = static::$serverAddress ?? null;

            if ($serverAddress === null && isset($_SERVER['SERVER_ADDR'])) {
                $serverAddress = $_SERVER['SERVER_ADDR'];
            }

            if ($serverAddress === null) {
                throw new Exception(
                    'Unable to resolve the server IP address. Please set it explicitly via ' . TorDetector::class . '::serverAddress()'
                );
            }

            return $serverAddress;
        }
    }

    /**
     * Resets the state.
     */
    public static function resetState(): void
    {
        static::$serverAddress = null;
    }

    /**
     * Checks if a user is currently reaching the server from a Tor exit node.
     *
     * @param string $clientAddress The IP of the visitor you'd like to check.
     * @param int    $port          The port on which the server is running.
     *
     * @return bool
     *
     * @throws Exception Please see the `static::serverAddress()` method.
     */
    public static function check(string $clientAddress, int $port = 80): bool
    {
        $detectHost = sprintf(
            '%s.%s.%s.ip-port.exitlist.torproject.org',
            static::reverseClientAddress($clientAddress),
            (string)$port,
            static::reverseClientAddress(static::serverAddress())
        );

        return gethostbyname($detectHost) === '127.0.0.2';
    }

    /**
     * This function simply reverses the IP's octets.
     *
     * @param string $ip The IP to be reversed.
     *
     * @return string
     */
    protected static function reverseClientAddress($ip): string
    {
        return join(
            '.',
            array_reverse(explode('.', $ip))
        );
    }
}

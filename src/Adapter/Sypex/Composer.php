<?php

namespace TimurFlush\GeoDetector\Adapter\Sypex;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use TimurFlush\GeoDetector\Adapter\Sypex;
use TimurFlush\GeoDetector\Exception;

class Composer
{
    public const DEFAULT_LINK = 'https://sypexgeo.net/files/SxGeoCountry.zip';

    /**
     * @param Event $event
     *
     * @throws \ReflectionException
     */
    public static function updateDatabase(Event $event)
    {
        static::writeln($event, '<warning> *** SxGeo database downloading *** </warning>');

        $extra = $event
            ->getComposer()
            ->getPackage()
            ->getExtra();

        $databaseLink = static::resolveDatabaseLink($event, $extra);

        static::writeln($event, 'Using the ' . $databaseLink);
        static::writeln($event, "Starting download...");

        $tmpDir  = sys_get_temp_dir();

        $zipFile = join(DIRECTORY_SEPARATOR, [
            $tmpDir, "sypex_db_" . md5(microtime()) . ".zip",
        ]);
        $zipResource = fopen($zipFile, "w");

        // @codeCoverageIgnoreStart
        if (!static::downloadDatabase($event, $databaseLink, $zipResource)) {
            static::writeln($event, '<error>Error downloading sypex database</error>');
            return;
        }
        // @codeCoverageIgnoreEnd

        static::writeln($event, 'Downloaded to' . $zipFile);
        static::writeln($event, 'Download completed.');
        static::writeln($event, 'Starting extraction...');

        $zip = new \ZipArchive();

        $extractPath = join(DIRECTORY_SEPARATOR, [
            $tmpDir, "sypex_db_" . md5(microtime())
        ]);
        $zipResult = $zip->open($zipFile);

        // @codeCoverageIgnoreStart
        if ($zipResult != true) {
            static::writeln($event, "<error>Extraction failed: error code ${zipResult}</error>");
            return;
        }
        // @codeCoverageIgnoreEnd

        $dbNameFromArchive = $zip->getNameIndex(0);

        /* Extract Zip File */
        $zip->extractTo($extractPath);
        $zip->close();

        // @codeCoverageIgnoreStart
        if (!unlink($zipFile)) {
            static::writeln($event, "<info> Unable to delete the temporary file ${zipFile} </info>");
        }
        // @codeCoverageIgnoreEnd

        static::writeln($event, "Extracted to ${extractPath}");

        if (isset($extra['TF_Sypex_PathToDatabase'])) {
            $fs = new Filesystem();

            if (!$fs->isAbsolutePath($pathToDatabase = $extra['TF_Sypex_PathToDatabase'])) {
                $appDir = static::resolveApplicationDirectory($event);
                $pathToDatabase = $fs->normalizePath($appDir . DIRECTORY_SEPARATOR . $pathToDatabase);
            }
        } else {
            $pathToDatabase = join(
                DIRECTORY_SEPARATOR,
                [
                    str_replace(
                        '/Sypex.php',
                        '',
                        (new \ReflectionClass(Sypex::class))->getFileName()
                    ),
                    Sypex::DEFAULT_FILE_NAME
                ]
            );
            static::writeln(
                $event,
                "<info>A database path `TF_Sypex_PathToDatabase` is not specified in composer extra, using default: ${pathToDatabase}</info>"
            );
        }

        static::writeln($event, "Copying to ${pathToDatabase}...");

        $intermediateDatabaseFile = $extractPath . DIRECTORY_SEPARATOR . $dbNameFromArchive;

        $copyResult = copy($intermediateDatabaseFile, $pathToDatabase);

        if ($copyResult) {
            static::writeln($event, "Copy completed.");
        } else {
            // @codeCoverageIgnoreStart
            static::writeln($event, "<error>Copy failed</error>");
            return;
            // @codeCoverageIgnoreEnd
        }

        // @codeCoverageIgnoreStart
        if (!unlink($intermediateDatabaseFile)) {
            static::writeln($event, "<info> Unable to delete the temporary file ${intermediateDatabaseFile} </info>");
        }
        // @codeCoverageIgnoreEnd

        static::writeln($event, "<warning> *** SxGeo database downloading finished *** </warning>");
    }

    /**
     * Write line to the command line.
     *
     * @param Event $event
     * @param string $message
     */
    protected static function writeln(Event $event, string $message): void
    {
        $event->getIO()->write($message);
    }

    /**
     * Resolve a database link.
     *
     * @param Event $event
     * @param array $extra
     *
     * @return string
     */
    protected static function resolveDatabaseLink(Event $event, array $extra): string
    {
        if (!isset($extra['TF_Sypex_Link'])) {
            $event->getIO()->write("<info>No database update url `TF_Sypex_Link` specified in composer extra, using default...</info>");
        }

        return $extra['TF_Sypex_Link'] ?? static::DEFAULT_LINK;
    }

    /**
     * Download a database.
     *
     * @param Event    $event
     * @param string   $link
     * @param resource $file
     *
     * @return bool
     */
    protected static function downloadDatabase(Event $event, string $link, $file): bool
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $link,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_NOPROGRESS => false,
            CURLOPT_FILE => $file,
            CURLOPT_PROGRESSFUNCTION => function ($ch, $totalBytes, $currentBytes) use ($event) {
                static $lastCurrent = null;

                if ($totalBytes > 0) {
                    $currentMegaBytes = number_format($currentBytes / (1024 * 1024), 2);
                    $totalMegaBytes = number_format($totalBytes / (1024 * 1024), 2);

                    if ($lastCurrent != $currentMegaBytes) {
                        $percent = $currentMegaBytes / ($totalMegaBytes / 100);

                        $event
                            ->getIO()
                            ->overwrite(
                                sprintf('%sMB/%sMB | %01.2f%%', $currentMegaBytes, $totalMegaBytes, $percent),
                                false
                            );

                        $lastCurrent = $currentMegaBytes;
                    }
                }
            }
        ]);

        $result = curl_exec($ch);

        // @codeCoverageIgnoreStart
        if (!$result) {
            static::writeln($event, sprintf("<error>Download failed: %s</error>", curl_error($ch)));
            return false;
        }
        // @codeCoverageIgnoreEnd

        curl_close($ch);

        return true;
    }

    /**
     * @param Event $event
     *
     * @return string
     */
    protected static function resolveApplicationDirectory(Event $event): string
    {
        return dirname($event->getComposer()->getConfig()->get('vendor-dir'));
    }
}

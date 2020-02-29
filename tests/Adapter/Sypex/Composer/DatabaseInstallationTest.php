<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex\Composer;

use Composer\Config as ComposerConfig;
use Composer\Package\RootPackage as ComposerRootPackage;
use Composer\Script\Event as ComposerEvent;
use Composer\IO\NullIO as ComposerIO;
use PHPUnit\Framework\TestCase;
use Composer\Composer as MessierComposer;
use Symfony\Component\Filesystem\Filesystem;
use TimurFlush\GeoDetector\Adapter\Sypex;
use TimurFlush\GeoDetector\Adapter\Sypex\Composer as TestScript;
use Mockery as m;

class DatabaseInstallationTest extends TestCase
{
    use CaseProvider;

    /**
     * @dataProvider caseProvider
     */
    public function testDatabaseInstallation($linkToDatabase = null, $pathToDatabase = null)
    {
        $extra = [];

        if (isset($linkToDatabase)) {
            $extra['TF_Sypex_Link'] = $linkToDatabase;
        }

        if (isset($pathToDatabase)) {
            $extra['TF_Sypex_PathToDatabase'] = $pathToDatabase;
        }

        if ($linkToDatabase === null && $pathToDatabase === null) {
            /*
             * Test of default behavior
             */
            $event          = [];
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
        }

        $event = $this->createEvent($extra);
        $fs    = new Filesystem();

        if (!$fs->isAbsolutePath($pathToDatabase)) {
            $pathToDatabase = dirname(VENDOR_DIR) . DIRECTORY_SEPARATOR . $pathToDatabase;
        }

        /*
         * Deffered unlink the file for next tests
         */
        register_shutdown_function(function () use ($pathToDatabase) {
            @unlink($pathToDatabase);
        });

        /*
         * Before running the script, we need to make sure that the file does not exist
         */
        $this->assertFileNotExists($pathToDatabase);

        /*
         * Run script
         */
        TestScript::updateDatabase($event);

        /*
         * After running the script, we need to make sure that the file exists
         */
        $this->assertFileExists($pathToDatabase);

        $z = new \ZipArchive();
        $result = $z->open($pathToDatabase);
        @$z->close();
        unset($z);

        /*
         * We need to know that the resulting file is not an archive
         */
        $this->assertEquals(\ZipArchive::ER_NOZIP, $result);

        $fh = fopen($pathToDatabase, 'rb');
        $header = fread($fh, 40);
        fclose($fh);
        unset($fh);

        /*
         * We need to know that the resulting file is SypexGeo database
         */
        $this->assertEquals('SxG', substr($header, 0, 3));
    }

    /**
     * Create composer event.
     *
     * @param array $extra
     *
     * @return ComposerEvent
     */
    protected function createEvent(array $extra = []): ComposerEvent
    {
        $config = m::mock(ComposerConfig::class);
        $config
            ->shouldReceive('get')
            ->withArgs(['vendor-dir'])
            ->andReturn(VENDOR_DIR);

        $package = m::mock(ComposerRootPackage::class);
        $package
            ->shouldReceive('getExtra')
            ->andReturn($extra);

        $composer = m::mock(MessierComposer::class);
        $composer
            ->shouldReceive('getPackage')
            ->andReturn($package);
        $composer
            ->shouldReceive('getConfig')
            ->andReturn($config);

        $event = m::mock(ComposerEvent::class);
        $event
            ->shouldReceive('getComposer')
            ->andReturn($composer);
        $event
            ->shouldReceive('getIO')
            ->andReturn(new ComposerIO());

        return $event;
    }
}

<?php

declare(strict_types=1);

namespace TimurFlush\GeoDetector\Tests\Adapter\Sypex\Composer;

trait CaseProvider
{
    /**
     * @return array
     */
    public function caseProvider()
    {
        return [
            [
                'link'  => $link = 'https://sypexgeo.net/files/SxGeoCountry.zip',
                'path'  => 'sypex-not-absolute.dat',
            ],
            [
                'link'  => $link = 'https://sypexgeo.net/files/SxGeoCountry.zip',
                'path'  => RESOURCE_DIR . '/sypex-absolute.dat',
            ],
            [
                // H E R E - E M P T Y - T O - T E S T - O F - D E F A U L T - B E H A V I O R
            ],
        ];
    }
}

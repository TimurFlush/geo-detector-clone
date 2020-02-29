[![Build Status](https://travis-ci.org/TimurFlush/geo-detector.svg?branch=master)](https://travis-ci.org/TimurFlush/geo-detector)
[![Coverage Status](https://coveralls.io/repos/github/TimurFlush/geo-detector/badge.svg?branch=master)](https://coveralls.io/github/TimurFlush/geo-detector?branch=master)


# GeoDetector
This library allows to receive geodata of any IP address. 

## Installation

```shell script
composer require timur-flush/geo-detector
```

## Available adapters

### SypexGeo (Local base)
Usage:
```php
use TimurFlush\GeoDetector\Adapter\Sypex;

# With custom database
$adapter = new Sypex('/path/to/custom/database');

# With custom mode
$adapter = new Sypex(null, Sypex::SXGEO_FILE | Sypex::SXGEO_MEMORY | Sypex::SXGEO_BATCH);

# With custom database & custom mode
$adapter = new Sypex('/path/to/custom/database', Sypex::SXGEO_FILE | Sypex::SXGEO_MEMORY | Sypex::SXGEO_BATCH);

# Default (also see auto-downloading)
$adapter = new Sypex();
```

You can use auto-downloading database. For example: composer.json
```json
"extra": {
  "TF_Sypex_Link": "http://link.to.sypex.database.archive.zip",
  "TF_Sypex_PathToDatabase": "path/to/db/in/your/project.dat"
},
"scripts": {
  "post-install-cmd": [
    "TimurFlush\\GeoDetector\\Adapter\\Sypex\\Composer::updateDatabase"
  ],
  "post-update-cmd": [
    "TimurFlush\\GeoDetector\\Adapter\\Sypex\\Composer::updateDatabase"
  ]
}
```

Please note that:
1. The `extra.TF_Sypex_Link` by default is `https://sypexgeo.net/files/SxGeoCountry.zip`
2. The `extra.TF_Sypex_PathToDatabase` by default is `./Adapter/Sypex/sypex-database.dat`
3. A default database path is `./Adapter/Sypex/sypex-database.dat`
3. Depending on the content of the Sypex database, you may not receive some geo information.

### SypexGeo (API)
Usage:
```php
use TimurFlush\GeoDetector\Adapter\SypexAPI;

# With custom SypexGeo server
$adapter = new SypexAPI('custom.server.com');

# With license key
$adapter = new SypexAPI(null, 'your-license-key');

# With custom server & license ky
$adapter = new SypexAPI('custom.server.com', 'your-license-key');

# Default
$adapter = new SypexAPI();
```

Please note that:
1. A server by default is `api.sypexgeo.net`
2. Without a license you can get only 10k request per month.

## Author
Name: Timur F.

Email: flush02@tutanota.com

## License
MIT
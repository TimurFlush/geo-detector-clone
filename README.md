[![Build Status](https://travis-ci.org/TimurFlush/geo-detector.svg?branch=2.x)](https://travis-ci.org/TimurFlush/geo-detector)
[![Coverage Status](https://coveralls.io/repos/github/TimurFlush/geo-detector/badge.svg?branch=2.x)](https://coveralls.io/github/TimurFlush/geo-detector?branch=2.x)


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

#### MaxMind
Usage:
```php
use TimurFlush\GeoDetector\Adapter\MaxMind;

$adapter = new MaxMind('path/to/database.mmdb');
```

#### MaxMind (API)
Usage:
```php
use TimurFlush\GeoDetector\Adapter\MaxMindAPI;

$accountId = 0;
$licenseKey = 'your-license-key';

$adapter = new MaxMindAPI($accountId, $licenseKey);
```

## Providing a geo data information
ProvideAll()
```php
use TimurFlush\GeoDetector\Entity\GeoData;
use TimurFlush\GeoDetector\Entity\Country;
use TimurFlush\GeoDetector\Entity\Region;
use TimurFlush\GeoDetector\Entity\City;

/**
 * Provides all geo data information
 * @var GeoData $geoData 
 */
$geoData = $adapter->provideAll('8.8.8.8');

$geoData->getClientAddress(); // 8.8.8.8

/**
 * @var Country $country 
 */
$country = $geoData->getCountry();
$country->getName(); // returns: United States of America
$country->getIso(); //  returns: US
$country->getLatitude(); // returns a latitude if it's exist
$country->getLongitude(); // returns a longitude if it's exist
$country->getTimeZone(); // returns a timezone if it's exist

/**
 * @var Region $region
 */
$region  = $geoData->getRegion();
$region->getName(); // returns a region name if it's exist
$region->getIso(); //  returns a region code if it's exist 
$region->getLatitude(); // returns a latitude if it's exist
$region->getLongitude(); // returns a longitude if it's exist
$region->getTimeZone(); // returns a timezone if it's exist

/**
 * @var City $city
 */
$city    = $geoData->getCity();
$city->getName(); // returns a region name if it's exist
$city->getIso(); //  returns a region code if it's exist 
$city->getLatitude(); // returns a latitude if it's exist
$city->getLongitude(); // returns a longitude if it's exist
$city->getTimeZone(); // returns a timezone if it's exist

$geoData->getTimeZone(); // get timezone in order city, region, country
$geoData->getLatitude(); // get latitude in order from city, region, country
$geoData->getLongitude(); // get longitude in order from city, region, country
$geoData->getTorStatis(); // determine if the address is an output node in the Tor network

$geoData->toJson(); // convert object to json
GeoData::createFromJson('{...}'); // create from json
```
---
ProvideCountry()
```php
/*
 * The behavior is identical to the provideCountry() method.
 * The difference is that it will return an array of country names.
 */
$adapter->provideCountriesBatch(['1.1.1.1', '8.8.8.8']); // ['AU', 'US']
```
---
ProvideAllBatch(array $array)
```php
/*
 * The behavior is identical to the provideAll() method.
 * The difference is that it will return an array of GeoData objects.
 */
$adapter->provideAllBatch(['1.1.1.1', '8.8.8.8']); // GeoData[]
```


## Author
Name: Timur F.

Email: flush02@tutanota.com

## License
BSD 3-Clause
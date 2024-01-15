# Edge Purge Twill Capsule

This Twill Capsule is intended to enable developers handle Edge cache purges. 

## Screenshots

### CMS configuration
![screenshot 1](docs/screenshot01.png)

![screenshot 2](docs/screenshot02.png)

## Installing

### Require the Composer package:

``` bash
composer require area17/twill-edge-purge
```

### Publish the configuration

``` bash
php artisan vendor:publish --provider="A17\TwillEdgePurge\ServiceProvider"
```

## Disabling

This package is enabled and injects itself automatically. To disable it you just need to add to `.env`:

```dotenv
TWILL_EDGE_PURGE_ENABLED=false
```

## Contribute

Please contribute to this project by submitting pull requests.

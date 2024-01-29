# Edge Purge Twill Capsule

This Twill Capsule is intended to enable developers handle Edge cache purges. 

## Screenshots

### Features on Twill
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

## Enabling

This package is disabled by default. To enable it you just need to add to `.env`:

```dotenv
TWILL_EDGE_PURGE_ENABLED=true
```

And set some user allowed roles:

```dotenv
TWILL_EDGE_PURGE_ALLOWED_ROLES=SUPERADMIN,ADMIN
```

## Add to the user menu

In order to show the "Flush CDN" menu option, add the directive below to the `_user.blade.php` file:

```blade
@TwillEdgPurgeUserMenu
```

## Configure the CDN service

```dotenv
# Twill Edge Purge
TWILL_EDGE_PURGE_SERVICE=cloudfront

# CloudFront configuration
CLOUDFRONT_KEY=
CLOUDFRONT_SECRET=
CLOUDFRONT_DISTRIBUTION=
CLOUDFRONT_REGION=eu-west-1
```

## CDN Sevices 
All CDN Services configurations are deferred to the `config/services.php` file. You can copy here the ones that you will use:

```php
'cloudfront' => [
    'key' => env('CLOUDFRONT_KEY', env('AWS_ACCESS_KEY_ID')),
    'secret' => env('CLOUDFRONT_SECRET', env('AWS_SECRET_ACCESS_KEY')),
    'sdk_version' => env('CLOUDFRONT_SDK_VERSION', env('AWS_SDK_VERSION', '2017-10-30')),
    'region' => env('CLOUDFRONT_REGION', env('AWS_DEFAULT_REGION', 'us-east-1')),
    'distribution' => env('CLOUDFRONT_DISTRIBUTION', env('AWS_CLOUDFRONT_DISTRIBUTION')),
],
```

## Adding checkbox to the edit page sidebar 
Import the FormSideBar trait into your Controller:

```php
use A17\TwillEdgePurge\Behaviours\FormSideBar;

class ArticleController extends BaseModuleController
{
    use FormSideBar;
```

## Setting up the repository to purge after save
Import the asldklaksd trait into your repository

```php
<?php

namespace App\Twill\Capsules\Articles\Repositories;

use A17\TwillEdgePurge\Behaviours\EdgePurgeAfterSave;

class ArticleRepository extends ModuleRepository
{
    use EdgePurgeAfterSave;
```

## Setting up the model
Import the Model trait and configure the route that generates the page URL, and optionally configure other URLs to be purged at the same time:

```php
<?php

namespace App\Twill\Capsules\Articles\Models;

use A17\TwillEdgePurge\Behaviours\EdgePurgeSavedModel;

class Article extends Model
{
    use EdgePurgeSavedModel;

    protected string|null $edgePurgePageRoute = 'article.page';

    /** You can add extra ulrs to be purged at the same you purge */
    protected array $edgePurgeExtraUrls = ['/blog', '/'];

    /** If the slug parameter on the route is not 'slug', you can set it here */
    protected string|null $edgePurgePageSlugParameter = 'type';
```

## Purging
When purging the URL for the current page, you can tell Twill to purge other related urls by declaring the `$edgePurgeExtraUrls` property. This is a handy feature when you are purging a page (a blog post) that may be present on a listing page (the /blog page).

```php
<?php

class Article extends Model
{
    use EdgePurgeSavedModel;

    protected array $edgePurgeExtraUrls = ['/blog', '/'];
```

## Jobs
To not risk blocking users from using the CMS on every update, this package may enqueue one of these two jobs to invalidate pages on the CDN: `EdgePurgeUrls` and `EdgePurgeAll`. This means that if your `QUEUE_CONNECTION` is not set to `sync`, you need to execute `php artisan queue:work` or have [Laravel Horizon](https://laravel.com/docs/10.x/horizon) running in order to see the invalidations/purges being sent to your CDN service.

## Contribute

Please contribute to this project by submitting pull requests.

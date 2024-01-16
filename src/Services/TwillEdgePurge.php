<?php

namespace A17\TwillEdgePurge\Services;

use A17\TwillEdgePurge\Jobs\EdgePurgeUrls;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use A17\TwillEdgePurge\Services\Cache\CloudFront;
use A17\TwillEdgePurge\Services\Cache\TwillEdgePurgeCacheService;

class TwillEdgePurge
{
    public function runningOnTwill(): bool
    {
        $prefix = config('twill.admin_route_name_prefix') ?? 'admin.';

        return Str::startsWith((string) Route::currentRouteName(), $prefix);
    }

    public function userMenu(): string
    {
        if (!$this->userCanPurge()) {
            return '';
        }
        
        $flushUrl = route('twill.TwillEdgePurge.flush-all');

        $flushLabel = twillTrans('Flush CDN');

        return "<a href=\"{$flushUrl}\">{$flushLabel}</a>";
    }

    public function userCanPurge(): bool
    {
        return $this->runningOnTwill() && $this->cdnIsConfigured() && $this->userIsAllowed();
    }

    public function userIsAllowed(): bool
    {
        $role = auth()->guard('twill_users')->user()->role;

        return in_array($role, config('twill-edge-purge.allowed.roles'));
    }

    public function cdnIsConfigured(): bool
    {
        return config('twill-edge-purge.enabled');
    }

    public function purge(array $urls)
    {
        EdgePurgeUrls::dispatch($urls);
    }

    public function purgeAll()
    {
        EdgePurgeUrls::dispatch('*');
    }

    public function purgeUrls(array $urls)
    {
        $this->serviceFactory()->purge($urls);
    }

    public function purgeAllUrls()
    {
        $this->serviceFactory()->purgeAll();
    }

    public function serviceFactory(): TwillEdgePurgeCacheService
    {
        $service = config('twill-edge-purge.service.name');

        if ($service === 'cloudfront') {
            return app(CloudFront::class);
        }

        if ($service === 'akamai') {
            return app(Akamai::class);
        }
    }
}
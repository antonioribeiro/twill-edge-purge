<?php

namespace A17\TwillEdgePurge\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use A17\TwillEdgePurge\Jobs\EdgePurgeAll;
use A17\TwillEdgePurge\Jobs\EdgePurgeUrls;
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
        $user = auth()
            ->guard('twill_users')
            ->user();

        if ($user === null) {
            return false;
        }

        /** @phpstan-ignore-next-line */
        return in_array($user->role, config('twill-edge-purge.allowed.roles'));
    }

    public function cdnIsConfigured(): bool
    {
        return config('twill-edge-purge.enabled');
    }

    public function purge(array $urls): void
    {
        EdgePurgeUrls::dispatch($urls);
    }

    public function purgeAll(): void
    {
        EdgePurgeAll::dispatch();
    }

    public function purgeUrls(array $urls): void
    {
        $this->serviceFactory()->purge($urls);
    }

    public function purgeAllUrls(): void
    {
        $this->serviceFactory()->purgeAll();
    }

    public function canDispatchInvalidations(): bool
    {
        return $this->serviceFactory()->canDispatchInvalidations();
    }

    public function serviceFactory(): TwillEdgePurgeCacheService
    {
        $service = config('twill-edge-purge.service.name');

        if ($service === 'cloudfront') {
            return app(CloudFront::class);
        }

        // if ($service === 'akamai') {
        //     return app(Akamai::class);
        // }

        throw new \Exception("Unknown service {$service}");
    }
}

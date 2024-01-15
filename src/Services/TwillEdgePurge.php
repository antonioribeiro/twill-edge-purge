<?php

namespace A17\TwillEdgePurge\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class TwillEdgePurge
{
    public function runningOnTwill(): bool
    {
        $prefix = config('twill.admin_route_name_prefix') ?? 'admin.';

        return Str::startsWith((string) Route::currentRouteName(), $prefix);
    }

    public function purgeAll()
    {
        
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
}
<?php

namespace A17\TwillEdgePurge\Services;

use Illuminate\Support\Str;
use A17\TwillEdgePurge\Services\TwillEdgePurge;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge as TwillEdgePurgeFacade;

class Helpers
{
    public static function load(): void
    {
        require __DIR__ . '/../Support/helpers.php';
    }

    public static function instance(): TwillEdgePurge
    {
        if (!app()->bound('edge-purge')) {
            app()->singleton('edge-purge', fn() => new TwillEdgePurge());
        }

        return app('edge-purge');
    }

    public static function nounce(): string
    {
        return TwillEdgePurgeFacade::nounce();
    }
}

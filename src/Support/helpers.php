<?php

use A17\TwillEdgePurge\Services\Helpers;
use A17\TwillEdgePurge\Services\TwillEdgePurge;

if (!function_exists('edge_purge')) {
    function edge_purge(): TwillEdgePurge
    {
        return Helpers::instance();
    }
}

if (!function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return Helpers::nounce();
    }
}

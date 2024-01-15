<?php

namespace A17\TwillEdgePurge\Support\Facades;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Facade;
use A17\TwillEdgePurge\Services\TwillEdgePurge as TwillEdgePurgeService;

/**
 * @method static Response middleware(Response $response, string $type = '*')
 */
class TwillEdgePurge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwillEdgePurgeService::class;
    }
}

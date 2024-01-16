<?php

namespace A17\TwillEdgePurge\Services\Cache;

interface TwillEdgePurgeCacheService
{
    public function purge(array $urls): void;

    public function purgeAll(): void;
}

<?php

namespace A17\TwillEdgePurge\Services;

use Illuminate\Support\Arr;
use A17\TwillEdgePurge\Repositories\TwillEdgePurgeRepository;
use A17\TwillEdgePurge\Models\TwillEdgePurge as TwillEdgePurgeModel;

trait Config
{
    public function config(string|null $key = null, mixed $default = null): mixed
    {
        $this->config ??= filled($this->config) ? $this->config : (array) config('twill-edge-purge');

        if (blank($key)) {
            return $this->config;
        }

        return Arr::get((array) $this->config, $key) ?? $default;
    }
}

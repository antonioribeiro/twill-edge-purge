<?php

namespace A17\TwillEdgePurge;

use Illuminate\Support\Str;
use A17\Twill\Helpers\Capsule;
use A17\Twill\Facades\TwillCapsules;
use Illuminate\Contracts\Http\Kernel;
use A17\Twill\TwillPackageServiceProvider;
use A17\TwillEdgePurge\Services\TwillEdgePurge;

class ServiceProvider extends TwillPackageServiceProvider
{
    /** @var bool $autoRegisterCapsules */
    protected $autoRegisterCapsules = false;

    protected Capsule $capsule;

    public function boot(): void
    {
        if ($this->registerConfig()) {
            $this->registerThisCapsule();

            $this->configureMiddleware();

            $this->registerViews();

            parent::boot();
        }
    }

    protected function registerThisCapsule(): void
    {
        $namespace = $this->getCapsuleNamespace();

        $this->capsule = TwillCapsules::registerPackageCapsule(
            Str::afterLast($namespace, '\\'),
            $namespace,
            $this->getPackageDirectory() . '/src',
            // null, // singular   ------------ Not available on Twill yet
            // true, // enabled   ------------ Not available on Twill yet
            // false, // automatic navigation   ------------ Not available on Twill yet
        );

        app()->singleton(TwillEdgePurge::class, fn() => new TwillEdgePurge());
    }

    public function registerConfig(): bool
    {
        $package = 'twill-edge-purge';

        $path = __DIR__ . "/config/{$package}.php";

        $this->mergeConfigFrom($path, $package);

        $this->publishes([
            $path => config_path("{$package}.php"),
        ]);

        return !!config('twill-edge-purge.enabled');
    }

    public function configureMiddleware(): void
    {
        if (!config('twill-edge-purge.middleware.inject')) {
            return;
        }

        /**
         * @phpstan-ignore-next-line
         * @var \Illuminate\Foundation\Http\Kernel $kernel
         */
        $kernel = $this->app[Kernel::class];

        foreach (config('twill-edge-purge.middleware.groups', []) as $middleware) {
            foreach ($middleware['classes'] as $class) {
                $middleware['type'] === 'prepend'
                    ? $kernel->prependMiddlewareToGroup($middleware['group'], $class)
                    : $kernel->appendMiddlewareToGroup($middleware['group'], $class);
            }
        }
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', Str::kebab($this->capsule->getPlural()));
    }
}

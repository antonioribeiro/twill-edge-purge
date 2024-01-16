<?php

namespace A17\TwillEdgePurge\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

class EdgePurgeUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const ALL_URLS = ['**all-urls**'];

    public array $urls;

    public function __construct(array|string $urls)
    {
        if (is_string($urls)) {
            if ($urls === '*') {
                $urls = self::ALL_URLS;
            } else {
                $urls = [$urls];
            }
        }

        $this->urls = $urls;
    }

    public function handle(): void
    {
        if ($this->urls === self::ALL_URLS) {
            TwillEdgePurge::purgeAllUrls();

            return;
        }

        TwillEdgePurge::purgeUrls($this->urls);
    }
}

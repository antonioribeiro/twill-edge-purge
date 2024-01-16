<?php

namespace A17\TwillEdgePurge\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

class EdgePurgeUrls implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public array $urls;

    public function __construct(array $urls)
    {
        $this->urls = $urls;
    }

    public function handle(): void
    {
        \Log::info('Purging URLs: ' . implode(', ', $this->urls));

        if (!TwillEdgePurge::canDispatchInvalidations()) {
            \Log::info('Deferred: ' . TwillEdgePurge::canDispatchInvalidations());

            $this->release(60); // seconds

            return;
        }

        \Log::info('Execute Purge');

        TwillEdgePurge::purgeUrls($this->urls);
    }
}

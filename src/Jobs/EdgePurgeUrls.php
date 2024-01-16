<?php

namespace A17\TwillEdgePurge\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;
use Illuminate\Support\Carbon;

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
        if (!TwillEdgePurge::canDispatchInvalidations()) {
            $this->release(20); // seconds

            return;
        }

        TwillEdgePurge::purgeUrls($this->urls);
    }

    public function retryUntil(): Carbon
    {
        return now()->addSeconds(20 * 2);
    }
}

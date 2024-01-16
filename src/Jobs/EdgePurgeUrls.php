<?php

namespace A17\TwillEdgePurge\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

class EdgePurgeUrls implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

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

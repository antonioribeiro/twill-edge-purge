<?php

namespace A17\TwillEdgePurge\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

class EdgePurgeAll implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        if (!TwillEdgePurge::canDispatchInvalidations()) {
            $this->release(60); // seconds

            return;
        }

        TwillEdgePurge::purgeAllUrls();
    }
}

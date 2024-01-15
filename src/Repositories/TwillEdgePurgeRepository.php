<?php

namespace A17\TwillEdgePurge\Repositories;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillEdgePurge\Models\TwillEdgePurge;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

/**
 * @method \Illuminate\Database\Eloquent\Builder published()
 */
class TwillEdgePurgeRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillEdgePurge $model)
    {
        $this->model = $model;
    }

    public function theOnlyOne(): TwillEdgePurge
    {
        $record = TwillEdgePurge::query()
            ->orderBy('id')
            ->first();

        return $record ?? $this->generate();
    }

    private function generate(): TwillEdgePurge
    {
        /** @var TwillEdgePurge $model */
        $model = app(self::class)->create([
            'hsts' => config('twill-edge-purge.headers.hsts.default')['value'],
            'csp_block' => config('twill-edge-purge.headers.csp.default')['block'],
            'csp_report_only' => config('twill-edge-purge.headers.csp.default')['report-only'],
            'expect_ct' => config('twill-edge-purge.headers.expect-ct.default')['value'],
            'xss_protection_policy' => config('twill-edge-purge.headers.xss-protection-policy.default')['value'],
            'x_frame_policy' => config('twill-edge-purge.headers.x-frame-policy.default')['value'],
            'x_content_type_policy' => config('twill-edge-purge.headers.x-content-type-policy.default')['value'],
            'referrer_policy' => config('twill-edge-purge.headers.referrer-policy.default')['value'],
            'permissions_policy' => config('twill-edge-purge.headers.permissions-policy.default')['value'],
            'unwanted_headers' => implode(',', config('twill-edge-purge.unwanted-headers')),
        ]);

        return $model;
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);

        $fields['headers'] = TwillEdgePurge::getAvailableHeaders();

        return $fields;
    }
}

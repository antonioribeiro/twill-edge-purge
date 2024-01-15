<?php

namespace A17\TwillEdgePurge\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\TwillEdgePurge\Models\TwillEdgePurge;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge as TwillEdgePurgeFacade;

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
        $model = app(self::class)->create([]);

        return $model;
    }

    public function getFormFields(TwillModelContract $object): array
    {
        $fields = parent::getFormFields($object);

        return $fields;
    }
}

<?php

namespace A17\TwillEdgePurge\Behaviours;

use A17\Twill\Models\Contracts\TwillModelContract;

trait EdgePurgeAfterSave
{
    public function afterSave(TwillModelContract $model, array $fields): void
    {
        parent::afterSave($model, $fields);

        if (!method_exists($model, 'edgePurgeAfterSave')) {
            return;
        }

        if ($fields['edge_purge_purge_this_page'] ?? false) {
            $model->edgePurgeAfterSave();
        }
    }
}

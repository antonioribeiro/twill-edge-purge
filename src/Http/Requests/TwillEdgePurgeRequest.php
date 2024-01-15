<?php

namespace A17\TwillEdgePurge\Http\Requests;

use A17\Twill\Http\Requests\Admin\Request;

class TwillEdgePurgeRequest extends Request
{
    public function rulesForCreate(): array
    {
        return [];
    }

    public function rulesForUpdate(): array
    {
        return [];
    }
}

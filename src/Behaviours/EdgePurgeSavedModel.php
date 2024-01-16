<?php

namespace A17\TwillEdgePurge\Behaviours;

use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

trait EdgePurgeSavedModel
{
    public bool $edgePurgeEnabled = true;

    public bool $edgePurgeExtraUrls = [];

    public string|null $edgePurgePageRoute = null;

    public function save(array $options = [])
    {
        $saved = parent::save($options);

        if ($this->edgePurgeEnabled) {
            $this->purgeOnEdge();
        }

        return $saved;
    }

    protected function purgeOnEdge(): void
    {
        TwillEdgePurge::purge($this->getEdgePurgeUrls());
    }

    protected function getEdgePurgeUrls(): array
    {
        return [$this->getPageUrl()] + $this->getEdgePurgeExtraUrls();
    }

    protected function getPageUrl(): string
    {
        if (empty($this->edgePurgePageRoute)) {
            return null;
        }

        return route($this->edgePurgePageRoute, ['slug' => $this->slug]);
    }

    protected function getEdgePurgeExtraUrls(): string
    {
        return $this->edgePurgeExtraUrls;
    }

    protected function enableEdgePurge(): array
    {
        return $this->edgePurgeEnabled = true;
    }

    protected function disableEdgePurge(): array
    {
        return $this->edgePurgeEnabled = false;
    }
}

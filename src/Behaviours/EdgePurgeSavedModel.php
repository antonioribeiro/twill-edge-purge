<?php

namespace A17\TwillEdgePurge\Behaviours;

use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge;

trait EdgePurgeSavedModel
{
    protected bool $edgePurgeEnabled = true;

    public function edgePurgeAfterSave(): void
    {
        if ($this->edgePurgeEnabled) {
            $this->purgeOnEdge();
        }
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
        if (!property_exists($this, 'edgePurgePageRoute')) {
            return null;
        }

        if (empty($this->edgePurgePageRoute)) {
            return null;
        }

        $slugParameter = $this->getEdgePurgeSlugParameter();

        return route($this->edgePurgePageRoute, [$slugParameter => $this->slug]);
    }

    protected function getEdgePurgeExtraUrls(): array
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

    protected function getEdgePurgeSlugParameter(): string
    {
        $parameter = 'slug';

        if (property_exists($this, 'edgePurgePageSlugParameter') && filled($this->edgePurgePageSlugParameter)) {
            $parameter = $this->edgePurgePageSlugParameter;
        }

        return $parameter;
    }
}

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
        return array_merge([$this->getPageUrl()], $this->getEdgePurgeExtraUrls());
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

        $url = route($this->edgePurgePageRoute, [$slugParameter => $this->slug]);

        return $this->extractUri($url);
    }

    protected function getEdgePurgeExtraUrls(): array
    {
        return [];
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

    public function extractUri(string $url): string
    {
        $uri = parse_url($url)['path'] ?? null;

        if (blank($uri)) {
            return '/';
        }

        return $uri;
    }
}

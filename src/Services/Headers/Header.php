<?php

namespace A17\TwillEdgePurge\Services\Headers;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use A17\TwillEdgePurge\Models\TwillEdgePurge;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\TwillEdgePurge\Repositories\TwillEdgePurgeRepository;

class Header
{
    protected TwillEdgePurge $edgePurge;

    public function __construct()
    {
        $this->edgePurge = $this->getModel();
    }

    public function setHeaders(Response|RedirectResponse|JsonResponse|BinaryFileResponse $response, array $header): void
    {
        if (!$this->enabled($header)) {
            return;
        }

        $responseHeader = $this->compileHeader($header);

        if (filled($responseHeader)) {
            $response->headers->set($header['header'], $responseHeader);
        }
    }

    protected function compileHeader(array $header): mixed
    {
        return $this->edgePurge->{$this->snake($header['type'])};
    }

    public function getModel(): TwillEdgePurge
    {
        return app(TwillEdgePurgeRepository::class)->theOnlyOne();
    }

    protected function enabled(array $header): bool
    {
        return $this->edgePurge->published &&
            $this->edgePurge->{$this->snake($header['type']) . '_enabled'};
    }

    public function snake(string $string): string
    {
        return Str::snake(Str::camel($string));
    }
}

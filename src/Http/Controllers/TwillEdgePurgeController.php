<?php

namespace A17\TwillEdgePurge\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use A17\TwillEdgePurge\Models\TwillEdgePurge;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillEdgePurge\Repositories\TwillEdgePurgeRepository;
use A17\TwillEdgePurge\Support\Facades\TwillEdgePurge as TwillEdgePurgeFacade;

class TwillEdgePurgeController extends ModuleController
{
    protected $moduleName = 'TwillEdgePurge';

    protected $titleColumnKey = 'site_key';

    protected $indexOptions = ['edit' => false];

    public function redirectToEdit(TwillEdgePurgeRepository $repository): RedirectResponse
    {
        return redirect()->route($this->namePrefix() . 'TwillEdgePurge.show', [
            'twillEdgePurge' => $repository->theOnlyOne()->id,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View|JsonResponse|RedirectResponse
     */
    public function index(int|null $parentModuleId = null): mixed
    {
        return redirect()->route($this->namePrefix() . 'TwillEdgePurge.redirectToEdit');
    }

    public function edit(TwillModelContract|int $id): mixed
    {
        $repository = new TwillEdgePurgeRepository(new TwillEdgePurge());

        return parent::edit($repository->theOnlyOne()->id);
    }

    protected function formData($request): array
    {
        return [
            'editableTitle' => false,
            'customTitle' => ' ',
        ];
    }

    protected function getViewPrefix(): string|null
    {
        return Str::kebab($this->moduleName) . '::admin';
    }

    private function namePrefix(): string|null
    {
        return config('twill.admin_route_name_prefix');
    }

    public function purgeAll(): RedirectResponse
    {
        TwillEdgePurgeFacade::purgeAll();

        return back();
    }
}

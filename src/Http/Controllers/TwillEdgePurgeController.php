<?php

namespace A17\TwillEdgePurge\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillEdgePurge\Models\TwillEdgePurge;
use A17\TwillEdgePurge\Repositories\TwillEdgePurgeRepository;

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
    public function index($parentModuleId = null)
    {
        return redirect()->route($this->namePrefix() . 'TwillEdgePurge.redirectToEdit');
    }

    public function edit($id, $submoduleId = null)
    {
        $repository = new TwillEdgePurgeRepository(new TwillEdgePurge());

        return parent::edit($repository->theOnlyOne()->id, $id);
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
}

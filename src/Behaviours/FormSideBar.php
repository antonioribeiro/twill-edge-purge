<?php

namespace A17\TwillEdgePurge\Behaviours;

use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Models\Contracts\TwillModelContract;

trait FormSideBar
{
    public function getSideFieldsets(TwillModelContract $model): Form
    {
        $form = new Form();

        $form->push(Checkbox::make()
             ->name('edge_purge_purge_this_page')
             ->label('Purge this page on CDN after save'));

        // Trying to customize the title is timing out the page load
        //      
        // $form->addFieldset(
        //     Fieldset::make()->title('CDN cache')->id('fieldset-cdn-cache')->fields([
        //         $form->push(Checkbox::make()
        //             ->name('edge_purge_purge_this_page')
        //             ->label('Purge this page on CDN after save'))
        //     ])
        // );
         
        return $form;
    }
}

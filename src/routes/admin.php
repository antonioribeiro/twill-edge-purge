<?php

use A17\TwillEdgePurge\Support\Facades\Route;

// @phpstan-ignore-next-line
Route::get('/TwillEdgePurge/flush-all', [
    'as' => 'TwillEdgePurge.flush-all', 
    'uses' => \A17\TwillEdgePurge\Http\Controllers\TwillEdgePurgeController::class . '@purgeAll'
]);

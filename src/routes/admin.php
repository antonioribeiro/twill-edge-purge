<?php

use A17\TwillEdgePurge\Support\Facades\Route;

// @phpstan-ignore-next-line
Route::name('TwillEdgePurge.redirectToEdit')->get('/TwillEdgePurge/redirectToEdit', [
    \A17\TwillEdgePurge\Http\Controllers\TwillEdgePurgeController::class,
    'redirectToEdit',
]);

// @phpstan-ignore-next-line
Route::module('TwillEdgePurge');

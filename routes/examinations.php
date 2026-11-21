<?php

use App\Http\Controllers\MarksheetController;

Route::middleware(['auth','role:Super Admin'])->group(function () {

    Route::get('/marksheets', [MarksheetController::class,'index'])
        ->name('marksheets.index');

});
require __DIR__.'/examinations.php';
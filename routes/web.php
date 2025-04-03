<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoicesController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('{student}/invoice/generate', [InvoicesController::class, 'generatePdf'])
    ->name('student.invoice.generate');
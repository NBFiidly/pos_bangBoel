<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::redirect('/', '/admin/login');

Route::get('/invoice/export-pdf', [InvoiceController::class, 'exportPdf'])->name('invoice.export-pdf');

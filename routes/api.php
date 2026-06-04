<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\PrinterQueueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::POST('/api/printer_queues_data', [ApiController::class,'printer_queues_data'])->name('printer_queues_data');
// Route::POST('/api/get_all_users', [ApiController::class,'get_all_users'])->name('get_all_users');
// Route::POST('/api/printer_list', [ApiController::class,'printer_list'])->name('printer_list');

// Route::POST('/api/final_store_label_print_content', [ApiController::class,'final_store_label_print_content'])->name('final_store_label_print_content');
// Route::POST('/api/store_number_print_content', [ApiController::class,'store_number_print_content'])->name('store_number_print_content');
// Route::POST('/api/printer_status_update', [ApiController::class,'printer_status_update'])->name('printer_status_update');
// Route::POST('/api/print_observer_api', [ApiController::class,'print_observer_api'])->name('print_observer_api');

// Route::POST('/api/delete_printer_queues', [ApiController::class, 'delete_printer_queues'])->name('delete_printer_queues');

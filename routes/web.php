<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApiListController;
use App\Http\Controllers\BlendingMeasuresController;
use App\Http\Controllers\BoxPredictedController;
use App\Http\Controllers\DirectorOfOperationsController;
use App\Http\Controllers\FloorEmployeeController;
use App\Http\Controllers\FloorManagerController;
use App\Http\Controllers\GuacamoleFloorEmployeeController;
use App\Http\Controllers\GuacamoleFloorManagerController;
use App\Http\Controllers\GuacamoleMeasureController;
use App\Http\Controllers\GuacamoleProductionManagerController;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\LabelInspectionController;
use App\Http\Controllers\MeasurementCategoryController;
use App\Http\Controllers\MetalDetectorController;
use App\Http\Controllers\MixMeasuresController;
use App\Http\Controllers\PortioningMeasureController;
use App\Http\Controllers\PrinterQueueController;
use App\Http\Controllers\ProductionManagerController;
use App\Http\Controllers\SafetyManagerController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Tony\ProductionManagerController as TonyProductionManagerController;
use App\Http\Controllers\Tony\TonyFloorEmployeeController;
use App\Http\Controllers\Tony\TonyFloorManagerController;
use App\Http\Controllers\UploadExcelController;
use App\Livewire\LabelInspectionItemMaster;
use App\Models\BlendingMeasure;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth'], function(){
    //Route::get('admin/company-list', [AdminAuthController::class, 'company_list'])->name('company_list');
    Route::get('admin/select-location', [AdminAuthController::class, 'select_location'])->name('select_location');
    Route::get('admin/work-type', [AdminAuthController::class, 'work_type'])->name('work_type');
    Route::get('admin/work_type_item_master', [AdminAuthController::class, 'work_type_item_master'])->name('work_type_item_master');
    Route::get('admin/portioning-measurement-form', [PortioningMeasureController::class, 'portioning_measurement_form'])->name('portioning_measurement_form');

    Route::get('admin/item-form', [PortioningMeasureController::class, 'item_form'])->name('item_form');
    Route::get('admin/portioning_measure_head', [PortioningMeasureController::class, 'portioning_measure_head'])->name('portioning_measure_head');

    Route::post('admin/add_measurement_action', [MeasurementCategoryController::class, 'add_measurement_action'])->name('add_measurement_action');
    Route::get('admin/measurement-category/{id}', [MeasurementCategoryController::class, 'measurement_category'])->name('measurement_category');
    Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Role bass route
    Route::get('blending_label_inspection_listing/{id}', [LabelInspectionController::class, 'blending_label_inspection_listing'])->name('blending_label_inspection_listing');
    Route::get('mix_label_inspection_listing/{id}', [LabelInspectionController::class, 'mix_label_inspection_listing'])->name('mix_label_inspection_listing');
    Route::get('md_label_inspection_listing/{id}', [LabelInspectionController::class, 'md_label_inspection_listing'])->name('md_label_inspection_listing');
    Route::get('gua_label_inspection_listing/{id}', [LabelInspectionController::class, 'gua_label_inspection_listing'])->name('gua_label_inspection_listing');

    Route::get('label_inspection_list/{id}', [LabelInspectionController::class, 'label_inspection_list'])->name('label_inspection_list');
    Route::get('label_inspection_list_filter/{id}', [LabelInspectionController::class, 'label_inspection_list_filter'])->name('label_inspection_list_filter');


    Route::get('label_inspection_details/{category_id}', [LabelInspectionController::class, 'label_inspection_details'])->name('label_inspection_details');
    Route::get('/get_category_item', [LabelInspectionController::class, 'get_category_item'])->name('get_category_item');

    // inspection Details Route
    Route::get('inspection_details_form', [LabelInspectionController::class, 'inspection_details_form'])->name('inspection_details_form');
    Route::post('inspection_details_form_action', [LabelInspectionController::class, 'inspection_details_form_action'])->name('inspection_details_form_action');
    Route::get('download_inspection_report/{daily_measure_id}', [LabelInspectionController::class, 'download_inspection_report'])->name('download_inspection_report');
    Route::get('remove_inspection_image', [LabelInspectionController::class, 'remove_inspection_image'])->name('remove_inspection_image');

    // Blending Route
    Route::get('admin/blending-list/{category_id}/{date_id}', [BlendingMeasuresController::class, 'blending_list'])->name('blending_list');
    Route::get('admin/blending-details/{item_id}/{date_id}/{batch_no}', [BlendingMeasuresController::class, 'blending_details'])->name('blending_details');
    Route::post('admin/blending-details-action', [BlendingMeasuresController::class, 'blending_details_action'])->name('blending_details_action');
    Route::post('admin/blending-details-update-action', [BlendingMeasuresController::class, 'blending_details_update_action'])->name('blending_details_update_action');
    Route::get('log_list/{blending_measure_id}', [BlendingMeasuresController::class, 'log_list'])->name('log_list');
    Route::get('admin/download-blending-excel/{daily_measure_id}', [BlendingMeasuresController::class, 'download_blending_excel'])->name('download_blending_excel');
    Route::get('admin/download-blending-pdf/{daily_measure_id}', [BlendingMeasuresController::class, 'download_blending_pdf'])->name('download_blending_pdf');

    Route::get('admin/add-blending-item', [BlendingMeasuresController::class, 'add_blending_item'])->name('add_blending_item');
    Route::post('admin/add-blending-item-action', [BlendingMeasuresController::class, 'add_blending_item_action'])->name('add_blending_item_action');
    Route::get('admin/blending-edit-items/{id}', [BlendingMeasuresController::class, 'blending_edit_items'])->name('blending_edit_items');
    Route::post('admin/blending-edit-item-action/{id}', [BlendingMeasuresController::class, 'edit_blending_item_action'])->name('edit_blending_item_action');
    Route::get('get_item_batch_no/{daily_measure_id}/{item_id}', [BlendingMeasuresController::class, 'get_item_batch_no'])->name('get_item_batch_no');
    Route::get('delete_blending_measure/{id}', [BlendingMeasuresController::class, 'delete_blending_measure'])->name('delete_blending_measure');

    // Mixing Route
    Route::get('admin/mixing-list/{category_id}/{date_id}', [MixMeasuresController::class, 'mix_list'])->name('mix_list');
    Route::get('admin/mix-details/{item_id}/{date_id}', [MixMeasuresController::class, 'blending_details'])->name('mixing_details');
    Route::post('admin/mix-measure-action', [MixMeasuresController::class, 'mix_measure_action'])->name('mix_measure_action');
    Route::post('admin/mix-measure-update-action', [MixMeasuresController::class, 'mix_measure_update_action'])->name('mix_measure_update_action');
    Route::get('admin/mix-log/{mixing_measure_id}', [MixMeasuresController::class, 'mix_log'])->name('mix_log');
    Route::get('admin/download-mix-excel/{daily_measure_id}', [MixMeasuresController::class, 'download_mix_excel'])->name('download_mix_excel');
    Route::get('admin/download-mix-measure-pdf/{daily_measure_id}', [MixMeasuresController::class, 'download_mix_measure_pdf'])->name('download_mix_measure_pdf');

    Route::get('admin/add-mix-item', [MixMeasuresController::class, 'add_mix_item'])->name('add_mix_item');
    Route::post('admin/add-mix-item-action', [MixMeasuresController::class, 'add_mix_item_action'])->name('add_mix_item_action');
    Route::get('admin/mix-edit-items/{id}', [MixMeasuresController::class, 'mix_edit_items'])->name('mix_edit_items');
    Route::post('admin/mix-edit-item-action/{id}', [MixMeasuresController::class, 'edit_mix_item_action'])->name('edit_mix_item_action');

    // Metal Detector Route
    Route::get('admin/metal-detector-list/{category_id}/{date_id}', [MetalDetectorController::class, 'metal_detector_list'])->name('metal_detector_list');
    Route::get('admin/metal_detective_details/{item_id}/{date_id}', [MetalDetectorController::class, 'metal_detective_details'])->name('metal_detective_details');
    Route::post('admin/metal-measure-action', [MetalDetectorController::class, 'metal_measure_action'])->name('metal_measure_action');
    Route::post('admin/metal-measure-update-action', [MetalDetectorController::class, 'metal_measure_update_action'])->name('metal_measure_update_action');
    Route::get('admin/metal-log/{mixing_measure_id}', [MetalDetectorController::class, 'metal_log'])->name('metal_log');
    Route::get('admin/download-metal-detector-excel/{daily_measure_id}', [MetalDetectorController::class, 'download_metal_detector_excel'])->name('download_metal_detector_excel');
    Route::get('admin/download-metal-detector-pdf/{daily_measure_id}', [MetalDetectorController::class, 'download_metal_detector_pdf'])->name('download_metal_detector_pdf');

    Route::get('admin/metal-detector-add-items', [MetalDetectorController::class, 'metal_detector_add_items'])->name('metal_detector_add_items');
    Route::post('admin/add-metal-detector-item-action', [MetalDetectorController::class, 'add_metal_detector_item_action'])->name('add_metal_detector_item_action');
    Route::get('admin/metal-detector-edit-items/{id}', [MetalDetectorController::class, 'metal_detector_edit_items'])->name('metal_detector_edit_items');
    Route::post('admin/metal-detector-edit-item-action/{id}', [MetalDetectorController::class, 'edit_metal_detector_item_action'])->name('edit_metal_detector_item_action');

    // Guacamole list Route
    Route::get('admin/guacamole-list/{category_id}/{date_id}', [GuacamoleMeasureController::class, 'guacamole_list'])->name('guacamole_list');
    Route::get('admin/guacamole_measure/{item_id}/{date_id}/{batch_no}', [GuacamoleMeasureController::class, 'guacamole_measure'])->name('guacamole_measure');
    Route::post('admin/guacamole-measure-action', [GuacamoleMeasureController::class, 'guacamole_measure_action'])->name('guacamole_measure_action');
    Route::post('admin/guacamole-measure-update-action', [GuacamoleMeasureController::class, 'guacamole_measure_update_action'])->name('guacamole_measure_update_action');
    Route::post('admin/update_box_bar_code_action', [GuacamoleMeasureController::class, 'update_box_bar_code_action'])->name('update_box_bar_code_action');
    Route::get('admin/guacamole-log/{guacamole_measure_id}', [GuacamoleMeasureController::class, 'guacamole_log'])->name('guacamole_log');
    Route::post('admin/read_bar_code', [GuacamoleMeasureController::class, 'read_bar_code'])->name('read_bar_code');
    Route::post('admin/store_container_br_code_action', [GuacamoleMeasureController::class, 'store_container_br_code_action'])->name('store_container_br_code_action');
    Route::get('admin/download-guacamole-excel/{daily_measure_id}', [GuacamoleMeasureController::class, 'download_guacamole_excel'])->name('download_guacamole_excel');
    Route::get('admin/remove-bar-code/{daily_measure_id}', [GuacamoleMeasureController::class, 'remove_bar_code'])->name('remove_bar_code');
    // Route::get('admin/download-guacamole-pdf/{daily_measure_id}', [GuacamoleMeasureController::class, 'download_guacamole_pdf'])->name('download_guacamole_pdf');
    Route::post('admin/download-guacamole-pdf', [GuacamoleMeasureController::class, 'download_guacamole_pdf'])->name('download_guacamole_pdf');
    Route::post('admin/generate_guac_report', [GuacamoleMeasureController::class, 'generate_guac_report'])->name('generate_guac_report');

    Route::get('admin/guacamole-add-items', [GuacamoleMeasureController::class, 'guacamole_add_items'])->name('guacamole_add_items');
    Route::post('admin/add-guacamole-item-action', [GuacamoleMeasureController::class, 'add_guacamole_item_action'])->name('add_guacamole_item_action');
    Route::get('admin/guacamole-edit-items/{id}', [GuacamoleMeasureController::class, 'guacamole_edit_items'])->name('guacamole_edit_items');
    Route::post('admin/guacamole-edit-item-action/{id}', [GuacamoleMeasureController::class, 'edit_guacamole_item_action'])->name('edit_guacamole_item_action');
    Route::get('get_guaco_item_batch_no/{daily_measure_id}/{item_id}', [GuacamoleMeasureController::class, 'get_guaco_item_batch_no'])->name('get_guaco_item_batch_no');
    Route::get('admin/create_new_guacamole_batch/{measurement_id}', [GuacamoleMeasureController::class, 'create_new_guacamole_batch'])->name('create_new_guacamole_batch');
    Route::get('delete_guacamole_measure/{id}', [GuacamoleMeasureController::class, 'delete_guacamole_measure'])->name('delete_guacamole_measure');

    // route for Safety manager
    Route::post('admin/verify_all_form_action', [SafetyManagerController::class, 'verify_all_form_action'])->name('verify_all_form_action');
    Route::get('admin/verify_by_id_action', [SafetyManagerController::class, 'verify_by_id_action'])->name('verify_by_id_action');
    Route::get('admin/unverified_measure_item/{measure_item_id}/{table_name}', [SafetyManagerController::class, 'unverified_measure_item'])->name('unverified_measure_item');

    Route::get('admin/item-master', [SafetyManagerController::class, 'item_master'])->name('item_master');
    Route::get('admin/item-list/{category_id}', [ItemMasterController::class, 'item_list'])->name('item_list');
    Route::get('admin/create_new_batch/{measurement_id}', [MeasurementCategoryController::class, 'create_new_batch'])->name('create_new_batch');

    // Label Inspection Items
    Route::get('admin/label-inspection-items', [LabelInspectionController::class, 'label_inspection_items'])->name('label_inspection_items');
    Route::get('/items/create', LabelInspectionItemMaster::class)->name('items.create');
    Route::get('/items/{id}/edit', LabelInspectionItemMaster::class)->name('items.edit');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AdminAuthController::class, 'admin_login'])->name('login');
    Route::post('/admin-login-action', [AdminAuthController::class, 'admin_login_action'])->name('admin_login_action');
});

// predict-boxes
// Route::get('/predict-boxes-all/{header_id}', [TestController::class, 'calculateBoxes'])->name('calculateBoxesAll');

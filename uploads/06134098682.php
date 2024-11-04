<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Create_Controller;
use App\Http\Controllers\booking_Controller;
use App\Http\Controllers\documents_Controller;
use App\Http\Controllers\login_Controller;
use App\Http\Controllers\tablevisiblity_Controller;
use App\Http\Controllers\verify_Controller;
use App\Http\Controllers\userlogin_Controller;
use App\Http\Controllers\payment_Controller;
use App\Http\Controllers\avilability_Controller;
use App\Http\Controllers\withdraw_Controller;
use App\Http\Controllers\adminmenu_Controller;
use App\Http\Controllers\payamount_Controller;
use App\Http\Controllers\receipt_Controller;
use App\Http\Controllers\transaction_Controller;
use App\Http\Controllers\allotment_Controller;
use App\Http\Controllers\amenities_Controller;
use App\Http\Controllers\amenity_update_Controller;







/*|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!

*/
 Route::post('log_user',[login_Controller::class,'rk_login']);
  Route::post('verify_amenity',[login_Controller::class,'verifyamenityotp']);

 
 Route::post('InsertData',[booking_Controller::class,'reg_ins_dt']);
 Route::post('typeData',[booking_Controller::class,'type_drp']);
  Route::post('table_data',[tablevisiblity_Controller::class,'tablevisible']);
  Route::post('delete_booking',[tablevisiblity_Controller::class,'bookingdelete']);
  
  Route::post('dialogue_data',[tablevisiblity_Controller::class,'dialogueData']);
 
  
 Route::post('check_data',[documents_Controller::class,'checking_data']);
  Route::post('check_status_data',[documents_Controller::class,'checking_status_data']);

Route::post('get_bookingdet',[documents_Controller::class,'get_booking_dt']);
Route::post('file_upload_af/{encryptedData}', [documents_Controller::class, 'document_upload_aaf']);
Route::post('file_upload_ab/{encryptedData}', [documents_Controller::class, 'document_upload_ab']);
Route::post('file_upload_pf/{encryptedData}', [documents_Controller::class, 'document_upload_pf']);
Route::post('file_upload_bpfv/{encryptedData}', [documents_Controller::class, 'document_upload_bpfv']);
Route::post('file_upload_uf/{encryptedData}', [documents_Controller::class, 'document_upload_uf']);
Route::post('file_upload_ub/{encryptedData}', [documents_Controller::class, 'document_upload_ub']);
Route::post('file_upload_idf/{encryptedData}', [documents_Controller::class, 'document_upload_idf']);
Route::post('file_upload_idbv/{encryptedData}', [documents_Controller::class, 'document_upload_idbv']);
Route::post('file_upload_af_se/{encryptedData}', [documents_Controller::class, 'document_upload_af_se']);
Route::post('file_upload_ab_se/{encryptedData}', [documents_Controller::class, 'document_upload_ab_se']);
Route::post('file_upload_uf_re/{encryptedData}', [documents_Controller::class, 'document_upload_uf_re']);
Route::post('file_upload_ub_re/{encryptedData}', [documents_Controller::class, 'document_upload_ub_re']);
Route::post('file_upload_ppfv_re/{encryptedData}', [documents_Controller::class, 'document_upload_ppfv_re']);
Route::post('file_upload_ppbv_re/{encryptedData}', [documents_Controller::class, 'document_upload_ppbv_re']);
Route::post('file_upload_afv_re/{encryptedData}', [documents_Controller::class, 'document_upload_afv_re']);
Route::post('file_upload_abv_re/{encryptedData}', [documents_Controller::class, 'document_upload_abv_re']);
Route::post('file_upload_bpfv_se/{encryptedData}', [documents_Controller::class, 'document_upload_bpfv_se']);
Route::post('file_upload_idfv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_idfv_psu']);
Route::post('file_upload_idbv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_idbv_psu']);
Route::post('file_upload_afv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_afv_psu']);
Route::post('file_upload_abv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_abv_psu']);
Route::post('file_upload_bpfv_re/{encryptedData}', [documents_Controller::class, 'document_upload_bpfv_re']);
Route::post('file_upload_bpfv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_bpfv_psu']);
Route::post('file_upload_dafv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_dafv_psu']);
Route::post('file_upload_dabv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_dabv_psu']);
Route::post('file_upload_dabv_re/{encryptedData}', [documents_Controller::class, 'document_upload_dabv_re']);
Route::post('file_upload_dafv_re/{encryptedData}', [documents_Controller::class, 'document_upload_dafv_re']);
Route::post('file_upload_dafv_se/{encryptedData}', [documents_Controller::class, 'document_upload_dafv_se']);
Route::post('file_upload_dabv_se/{encryptedData}', [documents_Controller::class, 'document_upload_dabv_se']);
Route::post('file_upload_psv_se/{encryptedData}', [documents_Controller::class, 'document_upload_psv_se']);
Route::post('file_upload_psv_psu/{encryptedData}', [documents_Controller::class, 'document_upload_psv_psu']);
Route::post('UpdateForm_lhscre/{encryptedData}', [documents_Controller::class, 'document_upload_lh_scre']);

Route::post('UpdateForm_lhscrm/{encryptedData}', [documents_Controller::class, 'document_upload_lh_scrm']);


Route::post('UpdateForm_lhscrov/{encryptedData}', [documents_Controller::class, 'document_upload_lh_scrov']);

Route::post('UpdateForm_lhscrpov/{encryptedData}', [documents_Controller::class, 'document_upload_lh_scrpov']);

Route::post('UpdateForm_lhscst/{encryptedData}', [documents_Controller::class, 'document_upload_lh_sc_st']);
Route::post('UpdateForm_lhrpf/{encryptedData}', [documents_Controller::class, 'document_upload_lh_rpf']);

Route::post('UpdateForm_lhobc/{encryptedData}', [documents_Controller::class, 'document_upload_lh_obc']);
Route::post('UpdateForm_lalitha/{encryptedData}', [documents_Controller::class, 'document_upload_lalitha']);
Route::post('UpdateForm_lhsc_rao/{encryptedData}', [documents_Controller::class, 'document_upload_lhrao']);
Route::post('UpdateForm_lhsc_rea/{encryptedData}', [documents_Controller::class, 'document_upload_lhrea']);






//verify

  Route::post('verify_booking',[verify_Controller::class,'verify']);
    Route::post('reject_booking',[verify_Controller::class,'reject']);
    
//userlogin
Route::post('sendmobile',[userlogin_Controller::class,'send_mobile']);
Route::post('verifyotp',[userlogin_Controller::class,'verifyotp']);

//payment

Route::post('verifydt',[payment_Controller::class,'getverifydata']);
//bill generation

Route::post('billdt',[payment_Controller::class,'bill_generation']);
//avilability
  Route::post('from_date_get',[avilability_Controller::class,'from_dates']);

//withdraw
Route::post('withdraw',[withdraw_Controller::class,'withdrawfun']);
//adminmenu
Route::post('verifyget',[adminmenu_Controller::class,'verify_get']);
Route::post('rejectget',[adminmenu_Controller::class,'reject_get']);
Route::post('withdrawget',[adminmenu_Controller::class,'withdraw_get']);
Route::post('revert',[withdraw_Controller::class,'revert_fun']);


//pay amount
Route::post('pay',[payamount_Controller::class,'getPayAmount']);
Route::post('tdata',[payamount_Controller::class,'transaction_data']);

//receipt
Route::post('recieptdata',[receipt_Controller::class,'getreceipt']);
//allot

Route::post('allotdata',[receipt_Controller::class,'setallot']);

//transaction

Route::post('get_transactiondet',[transaction_Controller::class,'transaction_get']);
Route::post('transaction_success',[transaction_Controller::class,'tansaction_success']);

//allotment controller

Route::post('allotment',[allotment_Controller::class,'allotment_get']);
  Route::post('verify_booking_admin',[allotment_Controller::class,'verify_admin']);
    Route::post('reject_booking_admin',[allotment_Controller::class,'reject_admin']);

// amenities_Controller

Route::post('amenityData',[amenities_Controller::class,'checkAndInsert']);

Route::post('getElectricalData',[amenities_Controller::class,'get_Electrical_Data']);
Route::post('getWaterData',[amenities_Controller::class,'get_Water_Data']);
Route::post('getEngineeringData',[amenities_Controller::class,'get_engineering_Data']);
Route::post('getSTData',[amenities_Controller::class,'get_ST_Data']);

//amenities_update_Controller

Route::post('updateElectricalData',[amenity_update_Controller::class,'update_Electrical_Data']);
Route::post('updateWaterData',[amenity_update_Controller::class,'update_Water_Data']);
Route::post('updateEngineeringData',[amenity_update_Controller::class,'update_engineering_Data']);
Route::post('updateSTData',[amenity_update_Controller::class,'update_ST_Data']);




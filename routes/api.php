<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingAuthController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\final_Controller;
use App\Http\Controllers\status_Controller;
use App\Http\Controllers\userstatus_Controller;
use App\Http\Controllers\excessamount_Controller;
use App\Http\Controllers\lockdates_Controller;
use App\Http\Controllers\settlement_Controller;
use App\Http\Controllers\refundstatement_Controller;
use App\Http\Controllers\electrical_masterdata_Controller;
use App\Http\Controllers\waterworks_masterworks_Controller;
use App\Http\Controllers\payment_extension;
use App\Http\Controllers\archivelist_Controller;

/*|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!

*/

//Route::post('log_user', [login_Controller::class, 'rk_login']);
//Route::post('verify_amenity', [login_Controller::class, 'verifyamenityotp']);

Route::post('login', [AuthController::class, 'login']);
Route::post('verify_amenity', [AuthController::class, 'verifyamenityotp']);




Route::prefix('bookings')->group(function () {

    Route::post('sendstatusotp', [userstatus_Controller::class, 'usersend_mobile']);
    Route::post('verifystatusotp', [userstatus_Controller::class, 'verifyUserotp']);

    Route::post('booking_login', [BookingAuthController::class, 'login']);
    Route::post('booking_login_verify_otp', [BookingAuthController::class, 'verifyOtp']);

    //    Route::post('sendmobile', [userlogin_Controller::class, 'send_mobile']);
    //    Route::post('verifyotp', [userlogin_Controller::class, 'verifyotp']);

    Route::middleware('auth:bookings')
        ->group(function () {
            Route::post('getstatusData', [status_Controller::class, 'get_statusData']);
            Route::post('getallotedstatusData', [status_Controller::class, 'get_alloted_statusData']);
        });

});





Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::post('table_data', [tablevisiblity_Controller::class, 'tablevisible']);


    Route::post('upload_aadhar', [login_Controller::class, 'uploadAadhar']);


    Route::post('InsertData', [booking_Controller::class, 'reg_ins_dt']);


    Route::post('typeData', [booking_Controller::class, 'type_drp']);
    Route::post('depData', [booking_Controller::class, 'dependent_drp']);
    Route::post('payData', [booking_Controller::class, 'payband_drp']);


    Route::post('delete_booking', [tablevisiblity_Controller::class, 'bookingdelete']);

    Route::post('dialogue_data', [tablevisiblity_Controller::class, 'dialogueData']);


    Route::post('check_data', [documents_Controller::class, 'checking_data']);
    Route::post('check_status_data', [documents_Controller::class, 'checking_status_data']);

    Route::post('get_bookingdet', [documents_Controller::class, 'get_booking_dt']);
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
    Route::post('file_upload_ser_re/{encryptedData}', [documents_Controller::class, 'document_upload_ser_re']);

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

    Route::post('verify_booking', [verify_Controller::class, 'verify']);
    Route::post('reject_booking', [verify_Controller::class, 'reject']);

//userlogin

//payment

    Route::post('verifydt', [payment_Controller::class, 'getverifydata']);
//bill generation

    Route::post('billdt', [payment_Controller::class, 'bill_generation']);
//avilability
    Route::post('from_date_get', [avilability_Controller::class, 'from_dates']);

//withdraw
    Route::post('withdraw', [withdraw_Controller::class, 'withdrawfun']);
//adminmenu
    Route::post('verifyget', [adminmenu_Controller::class, 'verify_get']);
    Route::post('rejectget', [adminmenu_Controller::class, 'reject_get']);
    Route::post('withdrawget', [adminmenu_Controller::class, 'withdraw_get']);
    Route::post('revert', [withdraw_Controller::class, 'revert_fun']);


//pay amount
    Route::post('pay', [payamount_Controller::class, 'getPayAmount']);
    Route::post('excesspay', [payamount_Controller::class, 'getexcessPayAmount']);


    Route::post('tdata', [payamount_Controller::class, 'transaction_data']);
    Route::post('excesstdata', [payamount_Controller::class, 'excess_transaction_data']);

//double_verification


    Route::post('verify_dv', [payamount_Controller::class, 'dv_verify_data']);
    Route::post('dv_id', [payamount_Controller::class, 'dv_id_data']);


//receipt
    Route::post('recieptdata', [receipt_Controller::class, 'getreceipt']);
    Route::post('statusrecieptdata', [receipt_Controller::class, 'getstatusreceipt']);

//allot

    Route::post('allotdata', [receipt_Controller::class, 'setallot']);

//transaction

    Route::post('get_transactiondet', [transaction_Controller::class, 'transaction_get']);
    Route::post('transaction_success', [transaction_Controller::class, 'tansaction_success']);

//allotment controller

    Route::post('allotment', [allotment_Controller::class, 'allotment_get']);
    Route::post('verify_booking_admin', [allotment_Controller::class, 'verify_admin']);
    Route::post('reject_booking_admin', [allotment_Controller::class, 'reject_admin']);
    Route::post('reject2get', [allotment_Controller::class, 'reject_get2']);

// amenities_Controller

    Route::post('amenityData', [amenities_Controller::class, 'checkAndInsert']);

    Route::post('getElectricalData', [amenities_Controller::class, 'get_Electrical_Data']);

    Route::post('get_electricalmaster', [amenities_Controller::class, 'Getelectrical_master']);

    Route::post('getWaterData', [amenities_Controller::class, 'get_Water_Data']);

    Route::post('get_watermaster', [amenities_Controller::class, 'GetWater_master']);

    Route::post('getEngineeringData', [amenities_Controller::class, 'get_engineering_Data']);

    Route::post('getSTData', [amenities_Controller::class, 'get_ST_Data']);

//amenities_update_Controller

    Route::post('updateElectricalData', [amenity_update_Controller::class, 'update_Electrical_Data']);
    Route::post('updateWaterData', [amenity_update_Controller::class, 'update_Water_Data']);
    Route::post('updateEngineeringData', [amenity_update_Controller::class, 'update_engineering_Data']);
    Route::post('updateSTData', [amenity_update_Controller::class, 'update_ST_Data']);
// Route::post('updatefinal_status',[amenity_update_Controller::class,'updateFinalStatus']);

//final
    Route::post('getfinalData', [final_Controller::class, 'final_get']);
    Route::post('update_final_ele_status', [final_Controller::class, 'get_final_electrical']);
    Route::post('update_final_engg_status', [final_Controller::class, 'get_final_engineering']);
    Route::post('update_final_water_status', [final_Controller::class, 'get_final_water']);
    Route::post('update_final_ST_status', [final_Controller::class, 'get_final_ST']);
    Route::post('update_final_trans_status', [final_Controller::class, 'get_final_transaction']);
    Route::post('insertgenerate', [final_Controller::class, 'insertExcessData']);


//calender

    Route::post('slotdata', [booking_Controller::class, 'get_slotdata']);

//status


//user status


//excessdata

    Route::post('getexcess', [excessamount_Controller::class, 'getExcessData']);

    Route::post('excessrecieptdata', [receipt_Controller::class, 'getexcessreceipt']);

//lock dates

    Route::post('lockdates', [lockdates_Controller::class, 'lock_ins_dt']);
    Route::post('get_settlement', [settlement_Controller::class, 'get_settlement_data']);
    Route::post('getlock', [lockdates_Controller::class, 'get_lock']);
//check lock

    Route::post('checklock', [lockdates_Controller::class, 'lock_check_dt']);
    Route::post('unlockdates', [lockdates_Controller::class, 'unlock_dt']);

//refund statement

    Route::post('get_refundstatement', [refundstatement_Controller::class, 'refund_statement']);
//electrical masterdata

    Route::post('ele_mas', [electrical_masterdata_Controller::class, 'Electrical_mas']);

    Route::post('ele_mas_update', [electrical_masterdata_Controller::class, 'Electrical_mas_update']);
    Route::post('get_elemas', [electrical_masterdata_Controller::class, 'GetElectrical_mas']);
    Route::post('ele_mas_edit', [electrical_masterdata_Controller::class, 'Electrical_mas_edit']);
    Route::post('ele_mas_delete', [electrical_masterdata_Controller::class, 'Electrical_mas_delete']);

//waterwoks masterdata

    Route::post('water_mas', [waterworks_masterworks_Controller::class, 'waterworks_mas']);

    Route::post('water_mas_update', [waterworks_masterworks_Controller::class, 'waterworks_mas_update']);
    Route::post('get_watermas', [waterworks_masterworks_Controller::class, 'GetWater_mas']);
    Route::post('water_mas_edit', [waterworks_masterworks_Controller::class, 'waterworks_mas_edit']);
    Route::post('water_mas_delete', [waterworks_masterworks_Controller::class, 'waterworks_mas_delete']);

//paynowdisableget

    Route::post('get_total', [allotment_Controller::class, 'totalget']);
//extension get
    Route::post('get_extend', [payment_extension::class, 'extension_get']);

//payment extend

    Route::post('extend_payment', [payment_extension::class, 'extendtime']);
//archeive list

    Route::post('getarchive', [archivelist_Controller::class, 'get_Archive']);


});


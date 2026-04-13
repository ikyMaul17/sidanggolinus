<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\RedirectToLoginByRole;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/tidak_aktif', [App\Http\Controllers\PenumpangHomeController::class, 'tidak_aktif'])->name('tidak_aktif');

Route::get('/', [App\Http\Controllers\PenumpangHomeController::class, 'index'])->name('home');
Route::get('/tutorial_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'tutorial_penumpang'])->name('tutorial_penumpang');

//register penumpang
Route::get('/register_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'register_penumpang'])->name('register_penumpang');
Route::post('/register_penumpang_store', [App\Http\Controllers\PenumpangHomeController::class, 'register_penumpang_store'])->name('register_penumpang_store');

Route::post('/reset_password', [App\Http\Controllers\PenumpangHomeController::class, 'reset_password'])->name('reset_password');
Route::get("/page_reset_password", function(){
    return view('page_reset_password');
 })->name('page_reset_password');

 Route::post('/store_reset_password', [App\Http\Controllers\PenumpangHomeController::class, 'store_reset_password'])->name('store_reset_password');
Route::get("/input_reset_password", function(){
return view('input_reset_password');
})->name('input_reset_password');

Route::get('get-jurusan/{fakultasId}', [App\Http\Controllers\PenumpangController::class, 'getJurusan']);

//route untuk auth
Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/authenticate_admin', 'authenticate_admin')->name('authenticate_admin');
    Route::get('/logout', 'logout')->name('logout');

    //login penumpang
    Route::get('/login_penumpang', 'login_penumpang')->name('login_penumpang');

    //login supir
    Route::get('/login_supir', 'login_supir')->name('login_supir');
});

// Route untuk admin
Route::middleware([RedirectToLoginByRole::class . ':admin'])->group(function () {
    //route ke admin
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    //route fakultas
    Route::get('/fakultas', [App\Http\Controllers\FakultasController::class, 'index']);
    Route::post('/fakultas/store', [App\Http\Controllers\FakultasController::class, 'store']);
    Route::get('/fakultas/delete/{id}', [App\Http\Controllers\FakultasController::class, 'destroy']);
    Route::put('/fakultas/update/{id}', [App\Http\Controllers\FakultasController::class, 'update']);

    //route jurusan
    Route::get('/jurusan', [App\Http\Controllers\JurusanController::class, 'index']);
    Route::post('/jurusan/store', [App\Http\Controllers\JurusanController::class, 'store']);
    Route::get('/jurusan/delete/{id}', [App\Http\Controllers\JurusanController::class, 'destroy']);
    Route::put('/jurusan/update/{id}', [App\Http\Controllers\JurusanController::class, 'update']);
    
    //route bus
    Route::get('/bus', [App\Http\Controllers\BusController::class, 'index']);
    Route::post('/bus/store', [App\Http\Controllers\BusController::class, 'store']);
    Route::get('/bus/delete/{id}', [App\Http\Controllers\BusController::class, 'destroy']);
    Route::put('/bus/update/{id}', [App\Http\Controllers\BusController::class, 'update']);

    //route penumpang
    Route::get('/penumpang', [App\Http\Controllers\PenumpangController::class, 'index']);
    Route::post('/penumpang/store', [App\Http\Controllers\PenumpangController::class, 'store']);
    Route::get('/penumpang/delete/{id}', [App\Http\Controllers\PenumpangController::class, 'destroy']);
    Route::put('/penumpang/update/{id}', [App\Http\Controllers\PenumpangController::class, 'update']);
    Route::put('/penumpang/status/{id}', [App\Http\Controllers\PenumpangController::class, 'status']);

    //route supir
    Route::get('/supir', [App\Http\Controllers\SupirController::class, 'index']);
    Route::post('/supir/store', [App\Http\Controllers\SupirController::class, 'store']);
    Route::get('/supir/delete/{id}', [App\Http\Controllers\SupirController::class, 'destroy']);
    Route::put('/supir/update/{id}', [App\Http\Controllers\SupirController::class, 'update']);

    //route inspection items
    Route::get('/inspection_items', [App\Http\Controllers\InspectionItemController::class, 'index']);
    Route::post('/inspection_items/store', [App\Http\Controllers\InspectionItemController::class, 'store']);
    Route::put('/inspection_items/update/{id}', [App\Http\Controllers\InspectionItemController::class, 'update']);
    Route::get('/inspection_items/delete/{id}', [App\Http\Controllers\InspectionItemController::class, 'destroy']);

    //route daily condition records
    Route::get('/daily_condition_records', [App\Http\Controllers\DailyConditionRecordController::class, 'index']);

    //route admin
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index']);
    Route::post('/admin/store', [App\Http\Controllers\AdminController::class, 'store']);
    Route::get('/admin/delete/{id}', [App\Http\Controllers\AdminController::class, 'destroy']);
    Route::put('/admin/update/{id}', [App\Http\Controllers\AdminController::class, 'update']);

    //route halte_pergi
    Route::get('/halte_pergi', [App\Http\Controllers\HaltePergiController::class, 'index']);
    Route::post('/halte_pergi/store', [App\Http\Controllers\HaltePergiController::class, 'store']);
    Route::get('/halte_pergi/delete/{id}', [App\Http\Controllers\HaltePergiController::class, 'destroy']);
    Route::put('/halte_pergi/update/{id}', [App\Http\Controllers\HaltePergiController::class, 'update']);

    //route halte_pulang
    Route::get('/halte_pulang', [App\Http\Controllers\HaltePulangController::class, 'index']);
    Route::post('/halte_pulang/store', [App\Http\Controllers\HaltePulangController::class, 'store']);
    Route::get('/halte_pulang/delete/{id}', [App\Http\Controllers\HaltePulangController::class, 'destroy']);
    Route::put('/halte_pulang/update/{id}', [App\Http\Controllers\HaltePulangController::class, 'update']);

    //route faq
    Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index']);
    Route::post('/faq/store', [App\Http\Controllers\FaqController::class, 'store']);
    Route::get('/faq/delete/{id}', [App\Http\Controllers\FaqController::class, 'destroy']);
    Route::put('/faq/update/{id}', [App\Http\Controllers\FaqController::class, 'update']);

    //route announcement
    Route::get('/announcement', [App\Http\Controllers\AnnouncementController::class, 'index']);
    Route::post('/announcement/store', [App\Http\Controllers\AnnouncementController::class, 'store']);
    Route::get('/announcement/delete/{id}', [App\Http\Controllers\AnnouncementController::class, 'destroy']);
    Route::put('/announcement/update/{id}', [App\Http\Controllers\AnnouncementController::class, 'update']);

    //route tutorial
    Route::get('/tutorial', [App\Http\Controllers\TutorialController::class, 'index']);
    Route::post('/tutorial/store', [App\Http\Controllers\TutorialController::class, 'store']);
    Route::get('/tutorial/delete/{id}', [App\Http\Controllers\TutorialController::class, 'destroy']);
    Route::put('/tutorial/update/{id}', [App\Http\Controllers\TutorialController::class, 'update']);

    //route history
    Route::get('/history_booking', [App\Http\Controllers\HistoryBookingController::class, 'index']);
    Route::get('/history_booking_pdf', [App\Http\Controllers\HistoryBookingController::class, 'exportPdf']);

    //route feedback list
    Route::get('/list_feedback_penumpang', [App\Http\Controllers\ListFeedbackController::class, 'list_feedback_penumpang']);
    Route::get('/list_feedback_penumpang_pdf', [App\Http\Controllers\ListFeedbackController::class, 'exportPdfPenumpang']);

    Route::get('/list_feedback_supir', [App\Http\Controllers\ListFeedbackController::class, 'list_feedback_supir']);
    Route::get('/list_feedback_supir_pdf', [App\Http\Controllers\ListFeedbackController::class, 'exportPdfSupir']);

    //route kendala
    Route::get('/list_kendala', [App\Http\Controllers\ListKendalaController::class, 'index']);
    Route::put('/update_status_kendala/{id}', [App\Http\Controllers\ListKendalaController::class, 'update_status)kendala']);

    //map tracking_map
    Route::get('/tracking_map', [App\Http\Controllers\TrackingMapController::class, 'tracking_map']);
    Route::get('/api/tracking_admin/bus-positions', [App\Http\Controllers\TrackingMapController::class, 'getBusPositions'])->name('admin_tracking.buses');
    Route::get('/api/tracking_admin/bus-stops', [App\Http\Controllers\TrackingMapController::class, 'getBusStops'])->name('admin_tracking.stops');

    //route pertanyaan
    Route::get('/pertanyaan', [App\Http\Controllers\PertanyaanController::class, 'index']);
    Route::post('/pertanyaan/store', [App\Http\Controllers\PertanyaanController::class, 'store']);
    Route::get('/pertanyaan/delete/{id}', [App\Http\Controllers\PertanyaanController::class, 'destroy']);
    Route::put('/pertanyaan/update/{id}', [App\Http\Controllers\PertanyaanController::class, 'update']);

    //route laporan umpan balik
    Route::get('/laporan_umpan_balik', [App\Http\Controllers\LaporanUmpanBalikController::class, 'index'])->name('laporan_umpan_balik');
    Route::get('/detail_laporan_umpan_balik/{id}', [App\Http\Controllers\LaporanUmpanBalikController::class, 'show'])->name('detail_laporan_umpan_balik');
    Route::post('/update_laporan_umpan_balik/{id}', [App\Http\Controllers\LaporanUmpanBalikController::class, 'updateStatus'])->name('update_laporan_umpan_balik');
    Route::post('/bulk_delete_laporan_umpan_balik', [App\Http\Controllers\LaporanUmpanBalikController::class, 'bulkDestroy'])->name('bulk_delete_laporan_umpan_balik');
    Route::get('/delete_laporan_umpan_balik/{id}', [App\Http\Controllers\LaporanUmpanBalikController::class, 'destroy'])->name('delete_laporan_umpan_balik');

});

// Route untuk penumpang
Route::middleware([RedirectToLoginByRole::class . ':penumpang'])->group(function () {

    Route::get('/booking', [App\Http\Controllers\PenumpangHomeController::class, 'booking'])->name('booking');

    //history
    Route::get('/history_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'history_penumpang'])->name('history_penumpang');
    Route::get('/history_penumpang/data', [App\Http\Controllers\PenumpangHomeController::class, 'history_data']);

    //feedback
    Route::get('/feedback_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'feedback_penumpang'])->name('feedback_penumpang');
    Route::get('/feedback_penumpang/data', [App\Http\Controllers\PenumpangHomeController::class, 'feedback_data']);
    Route::post('/feedback_penumpang/store', [App\Http\Controllers\PenumpangHomeController::class, 'feedback_store']);

    Route::get('/tracking', [App\Http\Controllers\PenumpangHomeController::class, 'tracking'])->name('tracking');

    Route::get('/profile_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'profile'])->name('profile_penumpang');
    Route::put('/update_profile_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'update_profile'])->name('update_profile_penumpang');

    //map track
    Route::get('/api/bus-positions', [App\Http\Controllers\PenumpangHomeController::class, 'getBusPositions'])->name('tracking.buses');
    Route::get('/api/bus-stops', [App\Http\Controllers\PenumpangHomeController::class, 'getBusStops'])->name('tracking.stops');
    Route::get('/api/get_estimasi_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'get_estimasi_penumpang'])->name('get_estimasi_penumpang');

    Route::get('/umpan_balik_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'umpan_balik_penumpang'])->name('umpan_balik_penumpang');
    Route::get('/umpan_balik_penumpang/pertanyaan', [App\Http\Controllers\PenumpangHomeController::class, 'pertanyaan_by_bus'])->name('pertanyaan_by_bus');
    Route::get('/list_umpan_balik_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'list_umpan_balik_penumpang'])->name('list_umpan_balik_penumpang');
    Route::post('/store_umpan_balik_penumpang', [App\Http\Controllers\PenumpangHomeController::class, 'store_umpan_balik_penumpang'])->name('store_umpan_balik_penumpang');
    Route::get('/detail_umpan_balik_penumpang/{id}', [App\Http\Controllers\PenumpangHomeController::class, 'detail_umpan_balik_penumpang'])->name('detail_umpan_balik_penumpang');

    //booking controller
    Route::post('/get-halte', [App\Http\Controllers\PenumpangBookingController::class, 'getHalte']);
    Route::post('/get-halte-tujuan', [App\Http\Controllers\PenumpangBookingController::class, 'getHalteTujuan']);
    Route::post('/get-estimasi', [App\Http\Controllers\PenumpangBookingController::class, 'getEstimasi']);
    Route::post('/submit_booking', [App\Http\Controllers\PenumpangBookingController::class, 'submit_booking']);
    Route::get('/book/{kode}', [App\Http\Controllers\PenumpangBookingController::class, 'submit_booking_ulang']);

    Route::get('/cancel_request', [App\Http\Controllers\PenumpangBookingController::class, 'cancel_request']);

    Route::get('/check-transaction-status', [App\Http\Controllers\PenumpangBookingController::class, 'checkTransactionStatus'])->name('check_transaction_status');
    Route::post('/update-transaction', [App\Http\Controllers\PenumpangBookingController::class, 'updateTransaction'])->name('update.transaction');

    Route::get('/check-transaction-status-konfirmasi', [App\Http\Controllers\PenumpangBookingController::class, 'checkTransactionStatusKonfirmasi'])->name('check_transaction_status_konfirmasi');
    Route::post('/update-transaction-konfirmasi', [App\Http\Controllers\PenumpangBookingController::class, 'updateTransactionKonfirmasi'])->name('update.transaction_konfirmasi');

    Route::get('/check-transaction-reminder', [App\Http\Controllers\PenumpangBookingController::class, 'checkTransactionReminder'])->name('check_transaction_reminder');
    Route::post('/update-transaction-reminder', [App\Http\Controllers\PenumpangBookingController::class, 'updateTransactionReminder'])->name('update.transaction_reminder');

    Route::get('/check-transaction-kendala', [App\Http\Controllers\PenumpangBookingController::class, 'checkTransactionKendala'])->name('check_transaction_kendala');
    Route::post('/update-transaction-kendala', [App\Http\Controllers\PenumpangBookingController::class, 'updateTransactionKendala'])->name('update.transaction_kendala');

   
});

// Route untuk supir
Route::middleware([RedirectToLoginByRole::class . ':supir'])->group(function () {

    Route::get('/home_supir', [App\Http\Controllers\SupirHomeController::class, 'index'])->name('home_supir');
    Route::get('/shuttle', [App\Http\Controllers\SupirHomeController::class, 'shuttle'])->name('shuttle');
    Route::get('/antrian', [App\Http\Controllers\SupirHomeController::class, 'antrian'])->name('antrian');

    //history
    Route::get('/history_supir', [App\Http\Controllers\SupirHomeController::class, 'history_supir'])->name('history_supir');

    //profile
    Route::get('/profile_supir', [App\Http\Controllers\SupirHomeController::class, 'profile'])->name('profile_supir');
    Route::put('/update_profile_supir', [App\Http\Controllers\SupirHomeController::class, 'update_profile'])->name('update_profile_supir');

    Route::post('/submit_halte', [App\Http\Controllers\SupirHomeController::class, 'submit_halte']);

    Route::post('/submit_kendala', [App\Http\Controllers\SupirHomeController::class, 'submit_kendala']);

    //feedback
    Route::get('/feedback_supir', [App\Http\Controllers\SupirHomeController::class, 'feedback_supir'])->name('feedback_supir');
    Route::get('/feedback_supir/data', [App\Http\Controllers\SupirHomeController::class, 'feedback_data']);
    Route::post('/feedback_supir/store', [App\Http\Controllers\SupirHomeController::class, 'feedback_store']);

    //setting bus
    Route::get('/setting_bus', [App\Http\Controllers\SupirHomeController::class, 'setting_bus'])->name('setting_bus');
    Route::put('/update_setting_bus', [App\Http\Controllers\SupirHomeController::class, 'update_setting_bus'])->name('update_setting_bus');

    //cek_harian_bus
    Route::get('/cek_harian_bus', [App\Http\Controllers\SupirHomeController::class, 'cek_harian_bus'])->name('cek_harian_bus');
    Route::post('/insert_cek_harian_bus', [App\Http\Controllers\SupirHomeController::class, 'insert_cek_harian_bus'])->name('insert_cek_harian_bus');

    //umpan_balik_supir
    Route::get('/umpan_balik_supir', [App\Http\Controllers\SupirHomeController::class, 'umpan_balik_supir'])->name('umpan_balik_supir');
    Route::get('/umpan_balik_supir/pertanyaan', [App\Http\Controllers\SupirHomeController::class, 'pertanyaan_by_bus_supir'])->name('pertanyaan_by_bus_supir');
    Route::get('/list_umpan_balik_supir', [App\Http\Controllers\SupirHomeController::class, 'list_umpan_balik_supir'])->name('list_umpan_balik_supir');
    Route::post('/store_umpan_balik_supir', [App\Http\Controllers\SupirHomeController::class, 'store_umpan_balik_supir'])->name('store_umpan_balik_supir');
    Route::get('/detail_umpan_balik_supir/{id}', [App\Http\Controllers\SupirHomeController::class, 'detail_umpan_balik_supir'])->name('detail_umpan_balik_supir');

});

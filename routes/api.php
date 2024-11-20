<?php

use App\Http\Controllers\TerminalController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsingController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PMReportController;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HR\AttendanceController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/tickets', function () {
    $users = Ticket::all();
    return response()->json($users);
});

Route::post('/import-csv', [TicketController::class, 'importCSV']);
Route::post('/import-pm', [TicketController::class, 'importPM']);
Route::get('issue', [IssueController::class, 'all']);
// Route::get('/pm', function () {
//     $pms = PMReport::all();
//     return response()->json($pms);
// });

Route::group(['prefix' => 'ticket'], function() {
    Route::put('update_pm/{id}', [PMReportController::class, 'update']);
    Route::delete('delete_pm/{pm_id}', [PMReportController::class, 'delete']);
});

Route::post('list_pm', [PMReportController::class, 'listPM']);
Route::post('spare_part', [PMReportController::class, 'addSparepart']);
Route::post('add_pm', [PMReportController::class, 'CreatePM']);



Route::get('using/all', [UsingController::class, 'getAll']);

Route::group(['prefix' => 'IMS'], function () {
    require __DIR__ . '/IMS/order.php';
    require __DIR__ . '/IMS/user.php';
    require __DIR__ . '/IMS/using.php';
    require __DIR__ . '/IMS/stock.php';
    require __DIR__ . '/IMS/mainpart.php';
    require __DIR__ . '/IMS/maintenace.php';
    require __DIR__ . '/IMS/spareparts.php';
    require __DIR__ . '/IMS/terminaltype.php';
    require __DIR__ . '/IMS/terminalmodel.php';
    require __DIR__ . '/IMS/customer.php';
    require __DIR__ . '/IMS/warehouse.php';
    require __DIR__ . '/IMS/site.php';
    require __DIR__ . '/IMS/banklocation.php';
    require __DIR__ . '/IMS/engineer.php';
    require __DIR__ . '/IMS/categorie.php';
    require __DIR__ . '/IMS/terminatstatus.php';
    require __DIR__ . '/IMS/terminal.php';
    require __DIR__ . '/IMS/ordermachine.php';
    require __DIR__ . '/IMS/addreplace.php';

    // new inventory management
    require __DIR__ . '/Inventory/Sparepart.php';
    require __DIR__ . '/Inventory/Stock.php';
    require __DIR__ . '/Inventory/SubItem.php';
    require __DIR__ . '/Inventory/RequestSpare.php';
    require __DIR__ . '/Inventory/ReturnSpare.php';
});

Route::group(['prefix' => 'HR'], function () {
    require __DIR__ . '/HR/staff.php';
    require __DIR__ . '/HR/department.php';
    require __DIR__ . '/HR/leave.php';
    require __DIR__ . '/HR/attendance.php';
    require __DIR__ . '/HR/leaveAnnual.php';
    require __DIR__ . '/HR/role.php';
});

Route::group(['prefix' => 'Log'], function () {
    require __DIR__ . '/LogFile/clientLog.php';
});




// Authentication
Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'change_password']);
});


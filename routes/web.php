<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;
use App\Models\Terminal;
use App\Models\Ticket;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TicketsImport;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/{vue_capture?}', function() {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

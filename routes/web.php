<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/index');
});
Route::get('index', [MessageController::class, 'index']);
Route::post('send_message', [MessageController::class, 'store']);
Route::get('get_mess',[MessageController::class, 'show']);
Route::post('del_mess', [MessageController::class, 'destroy']);
Route::post('update_mess', [MessageController::class, 'update']);

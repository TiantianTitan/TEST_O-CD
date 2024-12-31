<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
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

Route::get('/', function () {
    return view('welcome');
});




Route::middleware(['auth'])->group(function () {
    Route::get('/people', [PersonController::class, 'index'])->name('people.index');
    Route::get('/people/create', [PersonController::class, 'create'])->name('people.create');
    Route::post('/people', [PersonController::class, 'store'])->name('people.store');
    Route::get('/people/{id}', [PersonController::class, 'show'])->name('people.show');
    Route::put('/people/{id}', [PersonController::class, 'update'])->name('people.update');

});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();





Route::get('/test-degree', function () {
    DB::enableQueryLog(); // 启用查询日志
    $timestart = microtime(true); // 记录开始时间

    $person = Person::findOrFail(84); // 测试的起点
    $degree = $person->getDegreeWith(1265); // 测试目标点

    // 输出结果
    return response()->json([
        'degree' => $degree,
        'time' => microtime(true) - $timestart, // 计算时间
        'nb_queries' => count(DB::getQueryLog()), // 查询次数
    ]);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

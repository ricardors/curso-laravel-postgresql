<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// Rota protegida pelo middleware 'auth:sanctum' para autenticação via Sanctum; Esta rota é útil para autenticação em aplicações frontend separadas do backend.
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Rotas protegidas pelo middleware 'api' para autenticação JWT
// Route::middleware(['api'])->group(function() {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::get('/getaccount', [AuthController::class, 'getaccount']);
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});


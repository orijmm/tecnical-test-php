<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('/sanctum/token', TokenController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [SupportTicketController::class, 'index']);
    Route::post('/tickets', [SupportTicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/assign', [SupportTicketController::class, 'assign']);
    //List of user with rol
    Route::get('/users', [UserController::class, 'index']);
});

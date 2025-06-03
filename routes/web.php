<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [ChatController::class, 'index'])->name('users');
    Route::get('/chat/{receiverID}', [ChatController::class, 'chat'])->name('chat');
    Route::post('/chat/{receiverID}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');
    Route::post('/online', [ChatController::class, 'setOnline']);
    Route::post('/offline', [ChatController::class, 'setOffline']);
});
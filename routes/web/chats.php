<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChatController;

// routes/web.php
// Route::middleware('auth')->group(function () {
    // Chat routes
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/start/{user}', [ChatController::class, 'startConversation'])->name('chat.start');
        Route::get('/{conversation}', [ChatController::class, 'conversation'])->name('chat.conversation');
        Route::post('/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.message.send');
        Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');
        Route::post('/group/create', [ChatController::class, 'createGroup'])->name('chat.group.create');
        Route::get('/{conversation}/new-messages', [ChatController::class, 'getNewMessages']);
        // Add AJAX routes
        Route::post('/{conversation}/message/ajax', [ChatController::class, 'sendMessageAjax'])->name('chat.message.send.ajax');
    });
// });
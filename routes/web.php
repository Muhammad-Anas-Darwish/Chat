<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ContactController;
use App\Models\ChatMessage;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'user' => Auth::user(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/messages/{user2Id:int}', [ChatController::class, 'getMessages'])->name('messages.getMessages');
    Route::post('/messages', [ChatController::class, 'createNewMessage'])->name('messages.store');
    Route::get('/messages/read/{id}', [ChatController::class, 'markMessageAsRead'])->name('messages.read');

    Route::get('/contacts/{id}', [ContactController::class, 'getContact'])->name('contacts.getContact')->where('id', '[0-9]+');
    Route::get('/contacts', [ContactController::class, 'getContacts'])->name('contacts.getContacts');
    Route::resource('contacts', ContactController::class)->only(['store', 'update', 'destroy']);

    Route::get('/blocks', [BlockController::class, 'getBlocks'])->name('blocks.getBlocks');
    Route::delete('/blocks/{user2Id}', [BlockController::class, 'destroy'])->name('blocks.destroy');
    Route::resource('blocks', BlockController::class)->only(['store']);
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/chat', function () {
        return Inertia::render('Chats/Container', [
            'userId' => Auth::id(),
        ]);
    })->name('chats.container');

    Route::get('/contacts/create', function () {
        return Inertia::render('Contacts/Create');
    })->name('contacts.create');

    Route::get('/contacts/edit/{contactId}', function () {
        return Inertia::render('Contacts/Edit');
    })->name('contacts.edit');
});

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

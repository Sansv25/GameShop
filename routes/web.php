<?php

use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('accounts/{gameAccount}', [\App\Http\Controllers\GameAccountController::class, 'show'])->name('accounts.show');

Route::get('dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return app(\App\Http\Controllers\DashboardController::class)->index();
    }
    return redirect()->route('wishlists.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');

    // Chat Routes - Static routes MUST come before dynamic {user} routes
    Route::post('chat/handover', [\App\Http\Controllers\ChatController::class, 'handover'])->name('chat.handover');
    Route::post('chat/enable-bot', [\App\Http\Controllers\ChatController::class, 'enableBot'])->name('chat.enable-bot');
    Route::post('chat/{user}/offer-price', [\App\Http\Controllers\ChatController::class, 'offerPrice'])->name('chat.offer-price');
    Route::get('chat/messages/{user}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::get('chat/{user}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('chat/{user}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store')->middleware('throttle:30,1'); // Limit 30 messages per minute

    // Wishlist Routes
    Route::get('wishlists', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlists.index');
    Route::post('wishlists', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlists.store');
    Route::delete('wishlists/{gameAccount}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlists.destroy');

    // Order Routes
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/success/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.success');

    // Catalog Route
    Route::get('catalog', [\App\Http\Controllers\GameAccountController::class, 'catalog'])->name('accounts.catalog');

    // Unread count API
    Route::get('api/unread-count', [\App\Http\Controllers\DashboardController::class, 'getUnreadCount'])->name('api.unread-count');

    // Temporary Image Upload
    Route::post('upload', [\App\Http\Controllers\TemporaryImageController::class, 'upload'])->name('upload');
    Route::delete('revert', [\App\Http\Controllers\TemporaryImageController::class, 'revert'])->name('revert');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('accounts', [\App\Http\Controllers\GameAccountController::class, 'index'])->name('accounts.index');
    Route::get('accounts/create', [\App\Http\Controllers\GameAccountController::class, 'create'])->name('accounts.create');
    Route::post('accounts', [\App\Http\Controllers\GameAccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{gameAccount}/edit', [\App\Http\Controllers\GameAccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{gameAccount}', [\App\Http\Controllers\GameAccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{gameAccount}', [\App\Http\Controllers\GameAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');

    // Chatbot Settings (Admin)
    Route::post('settings/chatbot/toggle', [\App\Http\Controllers\ChatController::class, 'toggleChatbot'])->name('settings.chatbot.toggle');
    Route::get('settings/ai', [\App\Http\Controllers\Admin\AiSettingController::class, 'edit'])->name('settings.ai.edit');
    Route::put('settings/ai', [\App\Http\Controllers\Admin\AiSettingController::class, 'update'])->name('settings.ai.update');

    // Broadcast
    Route::get('broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'create'])->name('broadcast.create');
    Route::post('broadcast', [\App\Http\Controllers\Admin\BroadcastController::class, 'store'])->name('broadcast.store');
});

require __DIR__ . '/auth.php';

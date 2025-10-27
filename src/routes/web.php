<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ItemController,
    Auth\RegisterController,
    Auth\AuthenticatedSessionController,
    CommentController,
    AddressController,
    ProfileController,
    ExhibitionController,
    PurchaseController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここではWebルートを定義します。
| これらのルートは RouteServiceProvider によって読み込まれ、
| "web" ミドルウェアグループに属します。
|
*/

// ===============================
// 🏠 トップページ（商品一覧）
// ===============================
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// ===============================
// 🔐 認証関連
// ===============================
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

// ===============================
// 📧 メール認証
// ===============================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    // 認証完了後はプロフィール編集ページへ
    return redirect()->route('profile.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '確認メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ===============================
// 👤 ログインユーザー専用ルート
// ===============================
Route::middleware('auth')->group(function () {

    // 🛍 マイリスト
    Route::get('/mylist', [ItemController::class, 'index'])->name('mylist');

    // 💳 購入関連
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item_id}/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

    // 🏠 配送先住所
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

    // 💬 コメント
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // 🛒 出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // 👤 マイページ
    Route::get('/mypage', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 🚪 ログアウト
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

});

// ===============================
// 📦 商品詳細（ログイン必須）
// ===============================
Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show')
    ->middleware('auth');


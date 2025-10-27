<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィールトップページ
    public function index(Request $request)
    {
        $user = Auth::user();

        // デフォルトは 'sell' タブ（出品した商品）
        $tab = $request->query('tab', 'selling');

        // タブごとの商品取得
        switch ($tab) {
            case 'purchased':
                $items = $user->purchasedItems; // 購入した商品
                break;
            case 'selling':
            default:
                $items = $user->sellingItems;   // 出品した商品
        }
        
        return view('profile.index', compact('user', 'items', 'tab'));

    }

    // プロフィール編集ページ
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // プロフィール更新処理
    public function update(ProfileRequest $request)
{
    $user = Auth::user();
    $validated = $request->validated();

    // アバター画像の処理
    if ($request->hasFile('avatar')) {
        if ($user->profile && $user->profile->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
            Storage::disk('public')->delete($user->profile->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $path;
    }

    // プロフィールが未作成なら作成
    if (!$user->profile) {
        $user->profile()->create($validated);
    } else {
        $user->profile->update($validated);
    }

    return redirect()->route('profile.index', ['tab' => 'selling'])
        ->with('success', 'プロフィールを更新しました。');
}
    

    
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // 配送先住所の編集画面
    public function edit($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        return view('purchase.address', compact('user', 'item'));
    }

    // 住所の更新処理
    public function update(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        // バリデーション済みデータの取得
        $validated = $request->validated();

        // ユーザー住所更新
        $user->update($validated);

        return redirect()->route('purchase.create', ['item_id' => $item_id])
                 ->with('success', '住所を更新しました。')
                 ->withInput($validated);
    }
}

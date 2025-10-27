<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        
        if (!$user->profile || !$user->profile->postcode || !$user->profile->address) {
            return redirect()->route('profile.edit')
                ->with('error', '購入前にプロフィール（住所）を設定してください。');
        }

    return view('purchase.purchase', compact('item', 'user'));
}

public function store(PurchaseRequest $request, $item_id)
{
    $item = Item::findOrFail($item_id);

    
    if ($item->price > 300000) {
        return back()->withErrors(['error' => 'Stripe決済は30万円以下の商品のみ対応しています。']);
    }

    
    if ($request->has('success')) {
       
        $alreadySold = DB::table('sold_items')->where('item_id', $item->id)->exists();

        if (!$alreadySold) {
            DB::table('sold_items')->insert([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'sending_postcode' => $request->input('sending_postcode', ''),
                'sending_address' => $request->input('sending_address', ''),
                'sending_building' => $request->input('sending_building', ''),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('items.index')->with('success', '購入が完了しました！');
    }

    
    if ($request->has('cancel')) {
        return redirect()->route('items.index')->withErrors(['error' => '購入がキャンセルされました。']);
    }

    
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $item->name,
                ],
                'unit_amount' => $item->price * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('purchase.success', ['item_id' => $item->id]),
        'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]),
    ]);

    return redirect($session->url);
}

public function success(Request $request, $item_id)
{
    $item = Item::findOrFail($item_id);

    
    $alreadySold = DB::table('sold_items')->where('item_id', $item->id)->exists();

    if (!$alreadySold) {
        DB::table('sold_items')->insert([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'sending_postcode' => $request->input('sending_postcode', ''),
            'sending_address' => $request->input('sending_address', ''),
            'sending_building' => $request->input('sending_building', ''),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('items.index')->with('success', '購入が完了しました！');
}

// ✅ キャンセル時のルート
public function cancel($item_id)
{
    return redirect()->route('items.index')->withErrors(['error' => '購入がキャンセルされました。']);
}
}

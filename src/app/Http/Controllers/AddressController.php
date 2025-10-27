<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    
    public function edit($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        return view('purchase.address', compact('user', 'item'));
    }

    
    public function update(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        
        $validated = $request->validated();

        
        $user->update($validated);

        return redirect()->route('purchase.create', ['item_id' => $item_id])
                 ->with('success', '住所を更新しました。')
                 ->withInput($validated);
    }
}

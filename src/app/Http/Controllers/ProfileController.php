<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        
        $tab = $request->query('tab', 'selling');

        
        switch ($tab) {
            case 'purchased':
                $items = $user->purchasedItems; 
                break;
            case 'selling':
            default:
                $items = $user->sellingItems;   
        }
        
        return view('profile.index', compact('user', 'items', 'tab'));

    }

   
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    
    public function update(ProfileRequest $request)
{
    $user = Auth::user();
    $validated = $request->validated();

    
    if ($request->hasFile('avatar')) {
        if ($user->profile && $user->profile->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
            Storage::disk('public')->delete($user->profile->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $path;
    }

    
    if (!$user->profile) {
        $user->profile()->create($validated);
    } else {
        $user->profile->update($validated);
    }

    return redirect()->route('profile.index', ['tab' => 'selling'])
        ->with('success', 'プロフィールを更新しました。');
}
    

    
}

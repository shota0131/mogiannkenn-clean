<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Like;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $user = auth()->user();
        $keyword = $request->input('keyword');

        if ($tab === 'mylist') {
            $items = $user
                ? $user->likedItems()->get() 
                : collect();
        } else {
            $query = Item::query();

            
            if (!empty($keyword)) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%");
                });
        }

        
        $items = $query->latest()->get();
    }

    return view('items.index', compact('items', 'tab', 'user', 'keyword'));
}

public function show($id, Request $request)
{   
    $condition = Condition::where('condition', $request->condition)->first();

    $item = Item::with(['condition', 'categories', 'comments.user', 'likes'])->findOrFail($id);

    if ($request->ajax() && $request->has('favorite')) {
        if (!auth()->check()) {
            return response()->json(['error' => 'ログインが必要です'], 403);
        }

        $user = auth()->user();

        
        $existingLike = $item->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            
            $existingLike->delete();
            $liked = false;
        } else {
            
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            $liked = true;
        }

        
        $item->load('likes');
        return response()->json([
            'likes_count' => $item->likes->count(),
            'liked' => $liked
        ]);
    }

    
    return view('items.show', compact('item'));
}

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
{   

    $imgPath = null;
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('items', 'public');
        }

        
        $condition = Condition::where('condition', $request->condition)->first();
        
        if (!$condition) {
            return back()->withErrors(['condition' => '選択された商品の状態は無効です']);
        }

        
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'condition_id' => $condition->id, 
            'brand' => $request->brand ?? null,
            'user_id' => auth()->id(),
            'img_path' => $imgPath,
        ]);

       
        $item->categories()->sync($request->category_id);

        return redirect()->route('items.index', $item->id)
                         ->with('success', '商品を出品しました');
    }


}

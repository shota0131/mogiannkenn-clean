<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $validated = $request->validated();

        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->route('items.show', ['item_id' => $item_id])
            ->with('success', 'コメントを投稿しました。');
    }
}


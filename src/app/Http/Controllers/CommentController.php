<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $validatedData = $request->validated();

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'content' => $validatedData['content'],
        ]);

        return redirect()->route('items.show', [
            'itemId' => $item->id,
        ]);
    }
}
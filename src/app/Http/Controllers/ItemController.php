<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    public function show($item_id)
    {
        $item = Item::with([
            'user',
            'condition',
            'categories',
            'comments.user',
            'purchase',
        ])
        ->withCount([
            'likes',
            'comments',
        ])
        ->findOrFail($item_id);

        $isLiked = auth()->check()
        ? $item->likes()
            ->where('user_id', auth()->id())
            ->exists()
        : false;

        return view('items.show', compact('item', 'isLiked'));
    }
}

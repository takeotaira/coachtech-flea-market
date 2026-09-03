<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $query = Item::with('purchase');

        if ($request->query('tab') === 'mylist') {
            if (auth()->check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }
        }

        if ($request->filled('keyword')) {
            $query->where(
                'name',
                'like',
                '%' . $request->keyword . '%'
            );
        }

        $items = $query->get();

        return view('items.index', compact('items'));
    }
}
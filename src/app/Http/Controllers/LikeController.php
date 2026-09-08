<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    public function store($itemId)
    {
        $item = Item::findOrFail($itemId);

        Like::firstOrCreate([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
        ]);

        return redirect()->route('items.show', [
            'itemId' => $item->id,
        ]);
    }

    public function destroy($itemId)
    {
        $item = Item::findOrFail($itemId);

        Like::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->delete();

        return redirect()->route('items.show', [
            'itemId' => $item->id,
        ]);
    }
}
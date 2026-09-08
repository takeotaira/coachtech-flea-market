<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function show($itemId)
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
            ->findOrFail($itemId);

        $isLiked = auth()->check()
            ? $item->likes()
                ->where('user_id', auth()->id())
                ->exists()
            : false;

        return view('items.show', compact('item', 'isLiked'));
    }

    public function index(Request $request)
    {
        $itemQuery = Item::with('purchase');
        $isMyList = $request->query('tab') === 'mylist';

        if ($isMyList && !auth()->check()) {
            $items = collect();

            return view('items.index', compact('items'));
        }

        if ($isMyList) {
            $itemQuery->whereHas('likes', function ($likeQuery) {
                $likeQuery->where('user_id', auth()->id());
            });
        } elseif (auth()->check()) {
            $itemQuery->where('user_id', '!=', auth()->id());
        }

        if ($request->filled('keyword')) {
            $itemQuery->where(
                'name',
                'like',
                '%' . $request->keyword . '%'
            );
        }

        $items = $itemQuery->get();

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validatedData = $request->validated();

        $item = DB::transaction(function () use ($request, $validatedData) {
            $imagePath = $request->file('image')->store('items', 'public');

            $item = $request->user()->items()->create([
                'condition_id' => $validatedData['condition_id'],
                'name' => $validatedData['name'],
                'brand_name' => $validatedData['brand_name'] ?? null,
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'image_path' => 'storage/' . $imagePath,
            ]);

            $item->categories()->sync($validatedData['categories']);

            return $item;
        });

        return redirect()->route('items.show', [
            'itemId' => $item->id,
        ]);
    }
}
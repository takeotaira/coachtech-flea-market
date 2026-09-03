<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $profile = $request->user()->profile;

        $sessionAddress = session()->get("purchase_address.{$item->id}");

        return view('purchases.create', compact(
            'item',
            'profile',
            'sessionAddress'
        ));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->purchase) {
            return redirect()->route('items.show', [
                'item_id' => $item->id,
            ]);
        }

        $user = $request->user();

        $address = session()->get("purchase_address.{$item->id}");

        if (!$address) {
            $profile = $user->profile;

            if ($profile) {
                $address = [
                    'postal_code' => $profile->postal_code,
                    'address' => $profile->address,
                    'building' => $profile->building,
                ];
            }
        }

        if (!$address) {
            return redirect()->route('purchases.address.edit', [
                'item_id' => $item->id,
            ]);
        }

        DB::transaction(function () use ($item, $user, $address, $request) {
            $item->purchase()->create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'shipping_postal_code' => $address['postal_code'],
                'shipping_address' => $address['address'],
                'shipping_building' => $address['building'] ?? null,
            ]);
        });

        return redirect()->route('items.show', [
            'item_id' => $item->id,
        ]);
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchases.address', compact('item'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        session()->put("purchase_address.{$item->id}", [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('purchases.create', [
            'item_id' => $item->id,
        ]);
    }
}


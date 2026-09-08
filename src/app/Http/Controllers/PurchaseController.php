<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private const PURCHASE_ADDRESS_SESSION_PREFIX = 'purchase_address.';

    public function create(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $profile = $request->user()->profile;
        $sessionAddress = session()->get(
            self::PURCHASE_ADDRESS_SESSION_PREFIX . $item->id
        );

        return view('purchases.create', compact(
            'item',
            'profile',
            'sessionAddress'
        ));
    }

    public function store(PurchaseRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        if ($item->purchase) {
            return redirect()->route('items.show', [
                'itemId' => $item->id,
            ]);
        }

        $user = $request->user();
        $shippingAddress = session()->get(
            self::PURCHASE_ADDRESS_SESSION_PREFIX . $item->id
        );

        if (!$shippingAddress && $user->profile) {
            $shippingAddress = [
                'postal_code' => $user->profile->postal_code,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ];
        }

        if (!$shippingAddress) {
            return redirect()->route('purchases.address.edit', [
                'itemId' => $item->id,
            ]);
        }

        $validatedData = $request->validated();

        $item->purchase()->create([
            'user_id' => $user->id,
            'payment_method' => $validatedData['payment_method'],
            'shipping_postal_code' => $shippingAddress['postal_code'],
            'shipping_address' => $shippingAddress['address'],
            'shipping_building' => $shippingAddress['building'] ?? null,
        ]);

        return redirect()->route('items.show', [
            'itemId' => $item->id,
        ]);
    }

    public function edit($itemId)
    {
        $item = Item::findOrFail($itemId);

        return view('purchases.address', compact('item'));
    }

    public function updateAddress(AddressRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $validatedData = $request->validated();

        session()->put(
            self::PURCHASE_ADDRESS_SESSION_PREFIX . $item->id,
            [
                'postal_code' => $validatedData['postal_code'],
                'address' => $validatedData['address'],
                'building' => $validatedData['building'] ?? null,
            ]
        );

        return redirect()->route('purchases.create', [
            'itemId' => $item->id,
        ]);
    }
}
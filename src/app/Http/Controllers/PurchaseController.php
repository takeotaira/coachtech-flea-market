<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PurchaseController extends Controller
{
    private const PURCHASE_ADDRESS_SESSION_PREFIX = 'purchase_address.';

    private const STRIPE_PAYMENT_METHODS = [
        'カード支払い' => 'card',
        'コンビニ支払い' => 'konbini',
    ];

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

        $stripePaymentMethod = self::STRIPE_PAYMENT_METHODS[
            $validatedData['payment_method']
        ];

        $stripe = new StripeClient(
            config('services.stripe.secret')
        );

        $checkoutSession = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => [
                $stripePaymentMethod,
            ],
            'customer_email' => $user->email,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ],
            ],
            'metadata' => [
                'user_id' => (string) $user->id,
                'item_id' => (string) $item->id,
                'payment_method' => $validatedData['payment_method'],
                'shipping_postal_code' => $shippingAddress['postal_code'],
                'shipping_address' => $shippingAddress['address'],
                'shipping_building' => $shippingAddress['building'] ?? '',
            ],
            'success_url' => route('purchases.success', [
                'itemId' => $item->id,
            ]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchases.create', [
                'itemId' => $item->id,
            ]),
        ]);

        return redirect()->away($checkoutSession->url);
    }

    public function success(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('purchases.create', [
                'itemId' => $item->id,
            ]);
        }

        $stripe = new StripeClient(
            config('services.stripe.secret')
        );

        $checkoutSession = $stripe->checkout->sessions->retrieve(
            $sessionId
        );

        if (
            $checkoutSession->status !== 'complete'
            || (int) $checkoutSession->metadata->user_id !== $request->user()->id
            || (int) $checkoutSession->metadata->item_id !== $item->id
        ) {
            abort(403);
        }

        if (!$item->purchase) {
            $item->purchase()->create([
                'user_id' => $request->user()->id,
                'payment_method' => $checkoutSession->metadata->payment_method,
                'shipping_postal_code' => $checkoutSession->metadata->shipping_postal_code,
                'shipping_address' => $checkoutSession->metadata->shipping_address,
                'shipping_building' =>
                $checkoutSession->metadata->shipping_building ?: null,
            ]);
        }

        session()->forget(
            self::PURCHASE_ADDRESS_SESSION_PREFIX . $item->id
        );

        return redirect()->route('items.index');
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
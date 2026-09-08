<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load('profile');

        return view('mypage.profile', compact('user'));
    }

    public function show(Request $request)
    {
        $user = $request->user()->load('profile');

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user->purchases()
                ->with('item.purchase')
                ->get()
                ->pluck('item');
        } else {
            $items = $user->items()
                ->with('purchase')
                ->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();
        $isFirstSetup = !$user->profile()->exists();

        $user->update([
            'name' => $validatedData['name'],
        ]);

        $profileData = [
            'postal_code' => $validatedData['postal_code'],
            'address' => $validatedData['address'],
            'building' => $validatedData['building'] ?? null,
        ];

        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')
                ->store('profiles', 'public');

            $profileData['profile_image'] = 'storage/' . $profileImagePath;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        if ($isFirstSetup) {
            return redirect()->route('items.index');
        }

        return redirect()->route('mypage');
    }
}
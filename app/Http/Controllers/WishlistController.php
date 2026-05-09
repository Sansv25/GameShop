<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('gameAccount')->latest()->get();
        return view('wishlists.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate(['game_account_id' => 'required|exists:game_accounts,id']);
        
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'game_account_id' => $request->game_account_id
        ]);

        return back()->with('success', 'Ditambahkan ke Wishlist!');
    }

    public function destroy(GameAccount $gameAccount)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('game_account_id', $gameAccount->id)
            ->delete();

        return back()->with('success', 'Dihapus dari Wishlist!');
    }
}

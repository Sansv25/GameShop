<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\GameAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('gameAccount')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $accountId = $request->query('account_id');
        $account = null;
        $negotiatedPrice = null;

        if ($accountId) {
            $account = GameAccount::where('hash_id', $accountId)->first();
            
            if (!$account) {
                return redirect()->route('orders.index')->with('error', 'Akun tidak ditemukan.');
            }
            if ($account->status !== 'available') {
                return redirect()->route('orders.index')->with('error', 'Akun ini sudah tidak tersedia.');
            }

            // Find active offer securely
            $adminId = \App\Models\User::where('role', 'admin')->first()->id ?? null;
            if ($adminId) {
                $activeOffer = \App\Models\Message::where('sender_id', $adminId)
                    ->where('receiver_id', Auth::id())
                    ->where('account_id', $account->id)
                    ->where('is_price_offer', true)
                    ->where('offer_valid_until', '>', now())
                    ->latest()
                    ->first();
                    
                if ($activeOffer) {
                    $negotiatedPrice = $activeOffer->offer_price;
                }
            }
        }

        return view('orders.create', compact('account', 'negotiatedPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_account_id' => 'required|exists:game_accounts,hash_id',
            'negotiated_price' => 'nullable|numeric|min:0'
        ]);
        
        $account = GameAccount::where('hash_id', $request->game_account_id)->first();

        if ($account->status !== 'available') {
            return back()->with('error', 'Akun ini sudah tidak tersedia.');
        }

        // Securely determine final price (ignore request parameter, fetch from DB)
        $finalPrice = $account->price;
        $adminId = \App\Models\User::where('role', 'admin')->first()->id ?? null;
        
        if ($adminId) {
            $activeOffer = \App\Models\Message::where('sender_id', $adminId)
                ->where('receiver_id', Auth::id())
                ->where('account_id', $account->id)
                ->where('is_price_offer', true)
                ->where('offer_valid_until', '>', now())
                ->latest()
                ->first();
                
            if ($activeOffer) {
                $finalPrice = $activeOffer->offer_price;
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'game_account_id' => $account->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'amount' => $finalPrice,
            'account_username' => $account->username,
            'account_password' => $account->password,
            'status' => 'completed', // Change to completed since we're showing credentials immediately
        ]);

        // Mark account as sold
        $account->update(['status' => 'sold']);

        return redirect()->route('orders.success', $order)->with('success', 'Selamat! Akun game Anda telah berhasil dibeli.');
    }

    public function show(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('gameAccount');
        return view('orders.success', compact('order'));
    }
}

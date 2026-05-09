<?php


namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Account statistics
        $totalSold = GameAccount::where('status', 'sold')->count();
        $totalAvailable = GameAccount::where('status', 'available')->count();

        // Get accounts by category
        $accountsByCategory = GameAccount::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // Chat statistics - unique users who chatted
        $chatToday = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })
            ->whereDate('created_at', today())
            ->select(DB::raw('IF(sender_id = ' . $userId . ', receiver_id, sender_id) as user_id'))
            ->distinct()
            ->count();

        $chatThisWeek = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->select(DB::raw('IF(sender_id = ' . $userId . ', receiver_id, sender_id) as user_id'))
            ->distinct()
            ->count();

        $chatThisMonth = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('IF(sender_id = ' . $userId . ', receiver_id, sender_id) as user_id'))
            ->distinct()
            ->count();

        // Unread messages count
        $unreadCount = Message::unreadCountFor($userId);

        return view('dashboard', compact(
            'totalSold',
            'totalAvailable',
            'accountsByCategory',
            'chatToday',
            'chatThisWeek',
            'chatThisMonth',
            'unreadCount'
        ));
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Message::unreadCountFor(Auth::id())
        ]);
    }
}

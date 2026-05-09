<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    public function create()
    {
        $recipientCount = User::where('role', 'user')->count();
        return view('admin.broadcast.create', compact('recipientCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $adminId = Auth::id();
        $recipients = User::where('role', 'user')->get();

        foreach ($recipients as $user) {
            Message::create([
                'sender_id' => $adminId,
                'receiver_id' => $user->id,
                'message' => sprintf("%s\n\n%s", $request->subject, $request->message),
                'is_read' => false,
                'is_auto_message' => false,
            ]);
        }

        return back()->with('success', 'Broadcast berhasil dikirim ke ' . $recipients->count() . ' pengguna.');
    }
}

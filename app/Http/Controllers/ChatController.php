<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Services\GeminiApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display list of conversations for Admin.
     */
    public function index()
    {
        $adminId = Auth::id();

        // Get IDs of users involved in chat with current user (admin)
        $userIds = Message::where('sender_id', $adminId)
            ->orWhere('receiver_id', $adminId)
            ->select(DB::raw('IF(sender_id = ' . $adminId . ', receiver_id, sender_id) as user_id'))
            ->distinct()
            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)->get();

        // Add unread count for each user
        $users->each(function ($user) use ($adminId) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->count();
        });

        $chatbotActive = Setting::isChatbotActive();
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $totalRevenue = \App\Models\Order::whereIn('status', ['paid', 'completed'])->sum('amount');
        $totalCustomers = User::where('role', 'user')->count();
        $topCategory = GameAccount::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->first();

        return view('admin.chat.index', compact('users', 'chatbotActive', 'totalOrders', 'pendingOrders', 'totalRevenue', 'totalCustomers', 'topCategory'));
    }

    /**
     * Show chat with a specific user.
     */
    public function show(Request $request, User $user)
    {
        $myId = Auth::id();
        $otherId = $user->id;
        $accountId = $request->query('account_id');
        $account = null;

        if ($accountId) {
            $account = GameAccount::where('hash_id', $accountId)->first();
        } else {
            // Try to find the latest account discussed in this conversation
            $latestAccountMessage = Message::where(function ($q) use ($myId, $otherId) {
                $q->where(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $myId)->where('receiver_id', $otherId);
                })->orWhere(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $otherId)->where('receiver_id', $myId);
                });
            })
                ->whereNotNull('account_id')
                ->latest()
                ->first();

            if ($latestAccountMessage) {
                $account = $latestAccountMessage->account;
            }
        }

        // Check if the current user is customer (not admin) and resolve bot state
        $isBotHandling = false;
        $chatbotGloballyActive = Setting::isChatbotActive();
        if (Auth::user()->role !== 'admin' && $chatbotGloballyActive) {
            $isBotHandling = Auth::user()->is_bot_active;
        }

        $cannedResponses = [];
        if (Auth::user()->role === 'admin') {
            $cannedResponses = \App\Models\CannedResponse::all();
        }

        // Prepare active account data for JS
        $activeAccountData = null;
        if ($account) {
            // Check for active offer
            $activeOffer = Message::where(function ($q) use ($myId, $otherId) {
                $q->where(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $myId)->where('receiver_id', $otherId);
                })->orWhere(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $otherId)->where('receiver_id', $myId);
                });
            })
            ->where('account_id', $account->id)
            ->where('is_price_offer', true)
            ->where('offer_valid_until', '>', now())
            ->latest()
            ->first();

            $activePrice = $activeOffer ? $activeOffer->offer_price : $account->price;

            $activeAccountData = [
                'id' => $account->hash_id,
                'title' => $account->title,
                'description' => $account->description,
                'price' => $activePrice,
                'original_price' => $account->price,
                'has_offer' => $activeOffer ? true : false,
                'formatted_price' => 'Rp ' . number_format($activePrice, 0, ',', '.'),
                'image_url' => Storage::url($account->image_path)
            ];
        }

        return view('chat.show', compact('user', 'account', 'activeAccountData', 'isBotHandling', 'chatbotGloballyActive', 'cannedResponses'));
    }

    /**
     * Fetch messages between auth user and specific user (for polling).
     */
    public function fetchMessages(User $user)
    {
        $myId = Auth::id();
        $otherId = $user->id;

        $messages = Message::where(function ($q) use ($myId, $otherId) {
            $q->where(function ($inner) use ($myId, $otherId) {
                $inner->where('sender_id', $myId)->where('receiver_id', $otherId);
            })->orWhere(function ($inner) use ($myId, $otherId) {
                $inner->where('sender_id', $otherId)->where('receiver_id', $myId);
            });
        })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to last 50 messages for better performance
            ->get()
            ->reverse() // Reverse to maintain chronological order
            ->values();

        // Transform messages to use hash_id for accounts
        $messages->transform(function ($msg) {
            if ($msg->account_id) {
                $acc = GameAccount::find($msg->account_id);
                $msg->account_hash_id = $acc ? $acc->hash_id : null;
            }
            return $msg;
        });

        // Mark as read
        Message::where('sender_id', $otherId)->where('receiver_id', $myId)->update(['is_read' => true]);

        // Find latest account context
        $accountIdFromRequest = request('account_id');
        $accountData = null;

        if ($accountIdFromRequest) {
            $acc = GameAccount::where('hash_id', $accountIdFromRequest)->first();
        } else {
            $latestAccountMessage = Message::where(function ($q) use ($myId, $otherId) {
                $q->where(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $myId)->where('receiver_id', $otherId);
                })->orWhere(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $otherId)->where('receiver_id', $myId);
                });
            })
                ->whereNotNull('account_id')
                ->latest()
                ->first();
            $acc = $latestAccountMessage ? $latestAccountMessage->account : null;
        }

        if ($acc) {
            $activePrice = $acc->price;
            
            // Check for active offer
            $activeOffer = Message::where(function ($q) use ($myId, $otherId) {
                $q->where(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $myId)->where('receiver_id', $otherId);
                })->orWhere(function ($inner) use ($myId, $otherId) {
                    $inner->where('sender_id', $otherId)->where('receiver_id', $myId);
                });
            })
            ->where('account_id', $acc->id)
            ->where('is_price_offer', true)
            ->where('offer_valid_until', '>', now())
            ->latest()
            ->first();

            if ($activeOffer) {
                $activePrice = $activeOffer->offer_price;
            }

            $accountData = [
                'id' => $acc->hash_id,
                'title' => $acc->title,
                'description' => $acc->description,
                'price' => $activePrice,
                'original_price' => $acc->price,
                'has_offer' => $activeOffer ? true : false,
                'formatted_price' => 'Rp ' . number_format($activePrice, 0, ',', '.'),
                'image_url' => Storage::url($acc->image_path),
            ];
        }

        // Include bot active state for the current user
        $isBotHandling = false;
        if (Auth::user()->role !== 'admin') {
            $isBotHandling = Auth::user()->is_bot_active && Setting::isChatbotActive();
        }

        return response()->json([
            'messages' => $messages,
            'latest_account' => $accountData,
            'is_bot_handling' => $isBotHandling,
        ]);
    }

    /**
     * Send a message.
     */
    public function store(Request $request, User $user)
    {
        try {
            // Validation: relaxed account_id check for debugging
            $request->validate([
                'message' => 'nullable|string',
                'image' => 'nullable',
                'account_id' => 'nullable'
            ]);

            if (!$request->message && !$request->image) {
                return response()->json(['error' => 'Message or image is required'], 422);
            }

            $data = [
                'sender_id' => Auth::id(),
                'receiver_id' => $user->id,
                'message' => $request->message,
                'account_id' => null, // Will be set below
                'account_title' => null,
                'is_read' => false,
            ];

            if ($request->filled('account_id')) {
                $account = GameAccount::where('hash_id', $request->account_id)->first();
                if ($account) {
                    $data['account_title'] = $account->title;
                    $data['account_id'] = $account->id;
                }
            }

            // Handle Image from FilePond (Temporary Folder ID)
            if ($request->filled('image') && !($request->hasFile('image'))) {
                $folder = $request->image;

                // Sanitize folder ID (remove potential Debugbar HTML if present)
                if (is_string($folder) && strpos($folder, '<') !== false) {
                    $folder = explode('<', $folder)[0];
                }
                $folder = trim($folder);

                $diskLocal = Storage::disk('local');
                $diskPublic = Storage::disk('public');

                $files = $diskLocal->files('tmp/' . $folder);

                if (!empty($files)) {
                    $tempPath = $files[0];
                    $filename = basename($tempPath);
                    $targetPath = 'chat-images/' . $filename;

                    // Use streams/get to copy safely
                    $content = $diskLocal->get($tempPath);
                    if ($content) {
                        $diskPublic->put($targetPath, $content);
                        $data['image_path'] = $targetPath;
                        $diskLocal->deleteDirectory('tmp/' . $folder);
                    }
                }
            } elseif ($request->hasFile('image')) {
                // Direct upload fallback
                $path = $request->file('image')->store('chat-images', 'public');
                $data['image_path'] = $path;
            }

            // Create Message
            $message = Message::create($data);

            // ================================================
            // AI CHATBOT AUTO-REPLY LOGIC
            // ================================================
            $aiReply = null;
            $aiError = null;

            // Determine the admin user (receiver)
            $sender = Auth::user();
            $receiver = $user;

            // Identify: Is the sender a customer and the receiver an admin?
            $senderIsCustomer = $sender->role !== 'admin';
            $receiverIsAdmin = $receiver->role === 'admin';

            if ($senderIsCustomer && $receiverIsAdmin) {
                $chatbotGloballyActive = Setting::isChatbotActive();
                $userBotActive = $sender->is_bot_active;

                if ($chatbotGloballyActive && $userBotActive && $request->message) {
                    // Call Gemini API
                    $gemini = new GeminiApiService();
                    $result = $gemini->generateReply($sender->id, $receiver->id, $request->message);

                    if ($result['success'] && $result['text']) {
                        $replyText = $result['text'];
                        $aiAccountId = $request->account_id;

                        // Parse [ACCOUNT_ID: {id}] tag if AI suggested a specific account
                        if (preg_match('/\[ACCOUNT_ID:\s*(\d+)\]/', $replyText, $matches)) {
                            $aiAccountId = $matches[1];
                            // Remove the tag from the text sent to user
                            $replyText = preg_replace('/\[ACCOUNT_ID:\s*\d+\]/', '', $replyText);
                            $replyText = trim($replyText);
                        }

                        // Save AI reply as a message from admin, flagged as auto_message
                        $aiReplyData = [
                            'sender_id' => $receiver->id, // Admin is "sending"
                            'receiver_id' => $sender->id,  // Customer receives
                            'message' => $replyText,
                            'account_id' => $aiAccountId,
                            'is_read' => false,
                            'is_auto_message' => true,
                        ];

                        if ($aiAccountId) {
                            $aiAccount = GameAccount::where('hash_id', $aiAccountId)->first();
                            if ($aiAccount) {
                                $aiReplyData['account_title'] = $aiAccount->title;
                            }
                        }

                        $aiReply = Message::create($aiReplyData);
                    } else {
                        // Pass the error to the frontend
                        $aiError = $result['error'];
                    }
                }
            }

            return response()->json([
                'message' => $message,
                'ai_reply' => $aiReply,
                'ai_error' => $aiError,
            ]);
        } catch (\Exception $e) {
            Log::error("Chat Store Error: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'target_user' => $user->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
                'type' => get_class($e)
            ], 500);
        }
    }

    /**
     * Handle customer requesting to talk to a human admin.
     * This disables AI chatbot for their session.
     */
    public function handover(Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $user->is_bot_active = false;
        $user->save();

        // Send a note to admin with the handover reason
        $adminUser = User::where('role', 'admin')->first();
        if ($adminUser && $request->filled('reason')) {
            Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $adminUser->id,
                'message' => "Permintaan bantuan manusia:\n" . $request->reason,
                'is_read' => false,
                'is_auto_message' => false,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Anda sekarang terhubung langsung dengan admin.']);
    }

    /**
     * Re-enable AI chatbot for the customer's session.
     */
    public function enableBot(Request $request)
    {
        $user = Auth::user();
        $user->is_bot_active = true;
        $user->save();

        return response()->json(['success' => true, 'message' => 'AI Assistant diaktifkan kembali.']);
    }

    /**
     * Toggle chatbot globally (Admin only).
     */
    public function toggleChatbot(Request $request)
    {
        $currentState = Setting::isChatbotActive();
        Setting::setValue('chatbot_active', $currentState ? '0' : '1');

        return response()->json([
            'success' => true,
            'chatbot_active' => !$currentState,
            'message' => !$currentState ? 'AI Chatbot diaktifkan.' : 'AI Chatbot dinonaktifkan.'
        ]);
    }

    /**
     * Admin sends a price offer to a customer for a specific game account.
     */
    public function offerPrice(Request $request, User $user)
    {
        // Only admin can send price offers
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'account_id' => 'required|exists:game_accounts,hash_id',
            'offer_price' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:500',
            'valid_hours' => 'nullable|integer|min:1|max:72',
        ]);

        $account = GameAccount::where('hash_id', $request->account_id)->first();

        if (!$account) {
            return response()->json([
                'error' => 'Akun penawaran tidak ditemukan. Silakan pilih akun yang valid.'
            ], 422);
        }

        // Validate offer price is less than or equal to original price
        if ($request->offer_price > $account->price) {
            return response()->json(
                ['error' => 'Harga penawaran tidak boleh lebih tinggi dari harga asli'],
                422
            );
        }

        $validHours = $request->valid_hours ?? 24;
        $offerValidUntil = now()->addHours($validHours);

        // Create the price offer message
        $messageText = "Penawaran Khusus! 🎁\n";
        if ($request->filled('message')) {
            $messageText .= $request->message . "\n\n";
        }
        $messageText .= "Akun: " . $account->title . "\n";
        $messageText .= "Harga Asli: Rp " . number_format($account->price, 0, ',', '.') . "\n";
        $messageText .= "Harga Penawaran: Rp " . number_format($request->offer_price, 0, ',', '.') . "\n";
        $savings = $account->price - $request->offer_price;
        $messageText .= "Hemat: Rp " . number_format($savings, 0, ',', '.') . "\n";
        $messageText .= "Berlaku hingga: " . $offerValidUntil->format('d M Y H:i');

        $priceOfferMessage = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'account_id' => $request->account_id,
            'account_title' => $account->title,
            'message' => $messageText,
            'is_price_offer' => true,
            'offer_price' => $request->offer_price,
            'offer_valid_until' => $offerValidUntil,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $priceOfferMessage,
            'savings' => $savings,
        ]);
    }
}

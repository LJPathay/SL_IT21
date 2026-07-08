<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display the user's inbox.
     */
    public function inbox(Request $request)
    {
        $query = Message::forUser(Auth::id())->with('sender');

        if ($request->filter === 'unread') {
            $query->unread();
        } elseif ($request->filter === 'read') {
            $query->read();
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        $unreadCount = Message::forUser(Auth::id())->unread()->count();

        return view('messages.inbox', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Display the user's sent messages.
     */
    public function sent(Request $request)
    {
        $messages = Message::sentBy(Auth::id())
            ->with('recipient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.sent', [
            'messages' => $messages,
        ]);
    }

    /**
     * Show the form for composing a new message.
     */
    public function create()
    {
        $currentUser = Auth::user();

        // Role-based recipient filtering
        $recipients = User::where('id', '!=', Auth::id())
            ->where('is_active', true)
            ->when($currentUser->role === 'student', function ($query) {
                // Students can message students and instructors
                $query->whereIn('role', ['student', 'instructor']);
            })
            ->when($currentUser->role === 'instructor', function ($query) {
                // Instructors can message students and instructors
                $query->whereIn('role', ['student', 'instructor']);
            })
            ->when($currentUser->role === 'admin', function ($query) {
                // Admins can message everyone except other admins
                $query->where('role', '!=', 'admin');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'department']);

        return view('messages.create', [
            'recipients' => $recipients,
        ]);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id|different:' . Auth::id(),
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ], [
            'recipient_id.required' => 'Please select a recipient.',
            'recipient_id.different' => 'You cannot send a message to yourself.',
            'subject.required' => 'Subject is required.',
            'body.required' => 'Message body is required.',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
        ]);

        // Run background security assessments (Social Engineering & Phishing Checks)
        try {
            $detectionService = app(\App\Services\SecurityDetectionService::class);
            
            // 3. Scan for Social Engineering manipulation tactics
            $detectionService->detectSocialEngineering(
                $validated['body'],
                'Message subject: ' . $validated['subject'],
                $message->id
            );

            // 4. Scan for Phishing Characteristics inside text
            $detectionService->detectPhishing(
                $validated['body'],
                Auth::user()->email,
                $validated['subject'],
                $message->id
            );
        } catch (\Exception $e) {
            // Silence exceptions to keep communication working
        }

        return redirect()->route('messages.inbox')->with('success', 'Message sent successfully!');
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message)
    {
        // Check if user is either sender or recipient
        if ($message->sender_id !== Auth::id() && $message->recipient_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read if user is recipient
        if ($message->recipient_id === Auth::id() && !$message->is_read) {
            $message->markAsRead();
        }

        $message->load('sender', 'recipient');

        return view('messages.show', [
            'message' => $message,
        ]);
    }

    /**
     * Delete the specified message.
     */
    public function destroy(Message $message)
    {
        // Check if user is either sender or recipient
        if ($message->sender_id !== Auth::id() && $message->recipient_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.inbox')->with('success', 'Message deleted successfully!');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Message $message)
    {
        if ($message->recipient_id !== Auth::id()) {
            abort(403);
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count.
     */
    public function unreadCount()
    {
        $count = Message::forUser(Auth::id())->unread()->count();

        return response()->json(['count' => $count]);
    }
}

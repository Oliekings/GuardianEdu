<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessagingController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->getScopedSchoolId();

        // Get all users in the same school except self
        $contacts = User::where('school_id', $schoolId)
            ->where('id', '!=', Auth::id())
            ->get(['id', 'name', 'role']);

        return Inertia::render('Communication/Chat', [
            'contacts' => $contacts,
        ]);
    }

    public function getMessages(User $contact)
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        abort_unless($contact->school_id === $schoolId, 403);

        $messages = Message::where('school_id', $schoolId)
            ->where(function ($q) use ($contact) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $contact->id);
            })
            ->orWhere(function ($q) use ($contact) {
                $q->where('sender_id', $contact->id())->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string',
        ]);

        $message = Message::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        return redirect()->back();
    }
}

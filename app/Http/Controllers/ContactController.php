<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /** Enregistrement d'un message depuis le site public. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:4000'],
        ], [], [
            'name' => 'nom',
            'phone' => 'téléphone',
            'message' => 'message',
        ]);

        ContactMessage::create($data);

        return back()->with('contact_sent', true);
    }

    /** Liste des messages (admin). */
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(20);

        return view('admin.messages', compact('messages'));
    }

    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['read_at' => $message->read_at ? null : now()]);

        return back();
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService) {}

    public function index(Request $request)
    {
        $contacts = $this->contactService->paginate($request->only(['status', 'search']));

        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contacts,
            'stats'    => $this->contactService->getStats(),
            'filters'  => $request->only(['status', 'search']),
        ]);
    }

    public function show(int $id)
    {
        $contact = Contact::with('property.translation')->findOrFail($id);
        $contact->markAsRead();

        return Inertia::render('Admin/Contacts/Show', [
            'contact' => $contact,
        ]);
    }

    public function markReplied(Request $request, int $id)
    {
        $request->validate(['notes' => 'nullable|string|max:1000']);
        $this->contactService->markAsReplied($id, $request->notes);

        return back()->with('success', 'Marked as replied.');
    }

    public function destroy(int $id)
    {
        $this->contactService->delete($id);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted.');
    }
}

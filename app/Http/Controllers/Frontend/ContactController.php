<?php

namespace App\Http\Controllers\Frontend;

use App\DTOs\Contact\ContactInquiryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactRequest;
use App\Services\ContactService;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService)
    {
    }

    public function index()
    {
        return Inertia::render('Public/Contact');
    }

    public function store(ContactRequest $request)
    {
        $dto = ContactInquiryDTO::fromRequest($request->validated());
        $this->contactService->submit($dto);

        return back()->with('success', __('messages.contact_success'));
    }
}

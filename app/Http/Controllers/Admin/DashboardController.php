<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Property;
use App\Services\ContactService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(private readonly ContactService $contactService)
    {
    }

    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'properties'    => Property::count(),
                'active_properties' => Property::active()->count(),
                'posts'         => Post::count(),
                'contacts'      => $this->contactService->getStats(),
            ],
            'recentContacts' => Contact::with('property.translation')
                ->latest()
                ->limit(5)
                ->get(),
            'featuredProperties' => Property::with(['translation', 'coverImage'])
                ->active()
                ->featured()
                ->limit(4)
                ->get(),
        ]);
    }
}

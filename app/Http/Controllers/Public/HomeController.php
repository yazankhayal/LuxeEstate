<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use App\Services\PropertyService;
use App\Services\SettingService;
use App\Models\Service;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService,
        private readonly BlogService     $blogService,
        private readonly SettingService  $settingService,
    ) {
    }

    public function index()
    {
        return Inertia::render('Public/Home', [
            'featured'       => $this->propertyService->getFeatured(6),
            'recentPosts'    => $this->blogService->getRecent(3),
            'services'       => Service::with('translation')
                                   ->where('is_active', true)
                                   ->orderBy('order')
                                   ->limit(6)
                                   ->get(),
        ]);
    }
}

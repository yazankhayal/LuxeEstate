<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        return Inertia::render('Public/About');
    }

    public function privacy()
    {
        return Inertia::render('Public/PrivacyPolicy');
    }

    public function terms()
    {
        return Inertia::render('Public/Terms');
    }
}

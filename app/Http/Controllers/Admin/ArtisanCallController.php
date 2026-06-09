<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ArtisanCallService;
use Exception;

class ArtisanCallController extends Controller
{
    public function __construct(private readonly ArtisanCallService $contactService)
    {
    }

    public function artisanCall($call = null)
    {
        if(!$call) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Your command is empty.');
        }
        try{
            $this->contactService->call($call);
            return redirect()->route('admin.dashboard')
                ->with('success', $call . ' run successfully.');
        }
        catch (Exception $exception){
            return redirect()->route('admin.dashboard')
                ->with('error', $exception->getMessage());
        }
    }
}

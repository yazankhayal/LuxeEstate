<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class ArtisanCallService
{
    public function call(string $command): int
    {
        return Artisan::call($command);
    }
}

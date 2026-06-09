<?php

namespace App\Providers;

use App\Services\Interfaces\PropertyServiceInterface;
use App\Services\PropertyService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use App\Mail\Transport\BrevoTransport;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind service interfaces to implementations
        $this->app->bind(PropertyServiceInterface::class, PropertyService::class);
    }

    public function boot(): void
    {
        if(env('MAIL_MAILER') === 'brevo'){
            Mail::extend('brevo', function () {
                return new BrevoTransport();
            });
        }
        // Gates for fine-grained permission checks in controllers/views
        Gate::define('manage-users',    fn($user) => $user->isSuperAdmin());
        Gate::define('manage-settings', fn($user) => $user->isSuperAdmin());
        Gate::define('edit-content',    fn($user) => $user->isEditor());
        Gate::define('view-admin',      fn($user) => $user->isAdmin());
    }
}

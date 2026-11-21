<?php

namespace App\Providers;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Login Audit
        |--------------------------------------------------------------------------
        */

        Event::listen(Login::class, function (Login $event) {

            AuditLogger::action(
                'login',
                'Authentication',
                'User logged in: ' . ($event->user->name ?? 'Unknown User')
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Logout Audit
        |--------------------------------------------------------------------------
        */

        Event::listen(Logout::class, function (Logout $event) {

            AuditLogger::action(
                'logout',
                'Authentication',
                'User logged out: ' . ($event->user->name ?? 'Unknown User')
            );

        });
    }
}
<?php

namespace App\Providers;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Dispatcher $events): void
    {
        $events->listen(
            SocialiteWasCalled::class,
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle'
        );
    }
}

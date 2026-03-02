<?php

namespace App\Providers;

use App\Models\Poll;
use App\Policies\PollPolicy;
use App\Repositories\Auth\LoginRepositoryInterface;
use App\Repositories\Auth\LoginRepository;
use App\Repositories\Auth\RegisterRepositoryInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Admin\DashboardRepository;
use App\Repositories\Admin\DashboardRepositoryInterface;
use App\Repositories\PollRepositoryInterface;
use App\Repositories\PollRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);

        $this->app->bind(PollRepositoryInterface::class, PollRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase();
        });

        Gate::policy(Poll::class, PollPolicy::class);
    }
}

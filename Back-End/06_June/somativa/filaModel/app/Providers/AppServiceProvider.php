<?php

namespace App\Providers;

use App\Events\PedidoCriado;
use App\Listeners\EnviarEmailPedidoRegistrado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('pt_BR');
        Number::useLocale('pt_BR');

        Gate::before(function ($user, $ability){
            return $user->hasRole('Admin') ? true : null; // Admin = acesso livre
        });

        // Register events and listeners
        Event::listen(
            PedidoCriado::class,
            EnviarEmailPedidoRegistrado::class,
        );
    }
}

<?php

namespace App\Infrastructure\Providers;

use App\Application\Auth\Contracts\AuthenticationThrottle;
use App\Application\Http\Contracts\ClientIpResolver;
use App\Infrastructure\Http\IlluminateClientIpResolver;
use App\Domain\Books\Repositories\BookRepository;
use App\Infrastructure\Auth\LaravelAuthenticationThrottle;
use App\Infrastructure\Persistence\Books\BookEloquentRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookRepository::class, BookEloquentRepository::class);
        $this->app->singleton(AuthenticationThrottle::class, LaravelAuthenticationThrottle::class);
        $this->app->singleton(ClientIpResolver::class, IlluminateClientIpResolver::class);
    }
}

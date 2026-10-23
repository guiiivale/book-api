<?php

namespace App\Infrastructure\Providers;

use App\Domain\Books\Repositories\BookRepository;
use App\Infrastructure\Persistence\Books\BookEloquentRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookRepository::class, BookEloquentRepository::class);
    }
}

<?php

namespace App\Providers;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Contact\ContactRepositoryInterface;
use App\Repositories\Contact\ContactRepository;
use App\Repositories\Block\BlockRepository;
use App\Repositories\Block\BlockRepositoryInterface;
use App\Repositories\ChatMessage\ChatMessageRepositoryInterface;
use App\Repositories\ChatMessage\ChatMessageRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            ContactRepositoryInterface::class,
            ContactRepository::class
        );

        $this->app->bind(
            BlockRepositoryInterface::class,
            BlockRepository::class
        );

        $this->app->bind(
            ChatMessageRepositoryInterface::class,
            ChatMessageRepository::class
        );
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\NewChatMessage;
use App\Events\UpdateContact;
use App\Events\ReadMessage;
use App\Listeners\SendChatMessageNotification;
use App\Listeners\SendUpdateContactNotification;
use App\Listeners\SendReadMessageNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewChatMessage::class => [
            SendChatMessageNotification::class,
        ],
        UpdateContact::class => [
            SendUpdateContactNotification::class,
        ],
        ReadMessage::class => [
            SendReadMessageNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

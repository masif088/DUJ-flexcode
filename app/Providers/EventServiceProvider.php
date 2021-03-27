<?php

namespace App\Providers;

use App\Models\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\VerifyProcess' => [
            'App\Listeners\VerifyProcessListener'
        ],
        'App\Events\RegisterProcess' => [
            'App\Listeners\RegisterProcessListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
           Log::create([
            'user_id' => $event->user->id,
            'message' => 'melakukan login #'.$event->user->name,
            'type' => 'user',
            'type_id' => $event->user->id
           ]);
        });
    }
}

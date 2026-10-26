<?php

namespace App\Listeners;

use App\Enums\AnalyticsEventType;
use App\Models\User;
use App\Services\Analytics\AnalyticsRecorder;
use Illuminate\Auth\Events\Login;
use Illuminate\Events\Dispatcher;

// A subscriber (not a handle() listener) so Laravel's auto-discovery cannot register it twice
class AnalyticsEventSubscriber
{
    public function __construct(private readonly AnalyticsRecorder $recorder) {}

    public function onLogin(Login $event): void
    {
        if ($event->user instanceof User) {
            $this->recorder->record(AnalyticsEventType::Login, $event->user);
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'onLogin',
        ];
    }
}

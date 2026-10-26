<?php

namespace App\Services\Analytics;

use App\Enums\AnalyticsEventType;
use App\Models\AnalyticsEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class AnalyticsRecorder
{
    public function record(AnalyticsEventType $type, ?User $user = null, ?Model $subject = null): void
    {
        // Tracking must never break the request it is observing
        try {
            AnalyticsEvent::create([
                'type' => $type,
                'user_id' => $user?->id,
                'user_type' => $user?->user_type,
                'subject_type' => $subject?->getMorphClass(),
                'subject_id' => $subject?->getKey(),
                'session_id' => $this->sessionId(),
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function sessionId(): ?string
    {
        return request()->hasSession() ? request()->session()->getId() : null;
    }
}

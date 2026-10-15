<?php

namespace App\Notifications\Support;

/**
 * Channel-agnostic description of a notification. Each concrete notification
 * builds one of these; AppNotification renders it for every channel
 * (web push, database inbox, ...), so content is defined exactly once.
 */
final class NotificationPayload
{
    /**
     * @param  string  $url  Where the user lands when they tap/click it.
     * @param  string|null  $image  Absolute URL of a large preview image.
     * @param  string|null  $tag  Same tag = replaces the earlier notification on the device.
     * @param  array<string, mixed>  $data  Extra machine-readable context.
     */
    public function __construct(
        public readonly string $title,
        public readonly string $body,
        public readonly string $url,
        public readonly ?string $image = null,
        public readonly ?string $tag = null,
        public readonly array $data = [],
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'image' => $this->image,
            'tag' => $this->tag,
            'data' => $this->data,
        ], fn ($value) => $value !== null && $value !== []);
    }
}

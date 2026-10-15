<?php

namespace App\Notifications;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Notifications\Support\NotificationPayload;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NewProductNotification extends AppNotification
{
    public function __construct(public readonly Product $product) {}

    /** Re-checked right before sending: skip if it was pulled while queued. */
    public function shouldSend(object $notifiable, string $channel): bool
    {
        return $this->product->status === ProductStatus::Active->value;
    }

    public function payload(object $notifiable): NotificationPayload
    {
        $product = $this->product;

        $details = array_filter([
            '₦'.number_format((float) $product->price),
            $product->location,
        ]);

        $image = Arr::first($product->images ?? []);

        return new NotificationPayload(
            title: Str::limit($product->title, 70),
            body: 'Just posted · '.implode(' · ', $details),
            url: route('product.show', $product->slug),
            image: $image ? asset($image) : null,
            tag: "product-{$product->id}",
            data: ['type' => 'new_product', 'product_id' => $product->id],
        );
    }
}

<?php

namespace App\Services\Products;

use App\Models\Category;
use Illuminate\Http\Request;

final class ListingPricing
{
    // Service listings carry no price, buyers contact the seller instead
    public static function isPriceless(mixed $categoryId): bool
    {
        return filled($categoryId) && Category::whereKey($categoryId)->where('type', 'service')->exists();
    }

    public static function rules(mixed $categoryId): array
    {
        if (self::isPriceless($categoryId)) {
            return [];
        }

        return [
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
        ];
    }

    public static function attributes(Request $request): array
    {
        if (self::isPriceless($request->input('category_id'))) {
            return ['price' => 0, 'old_price' => null, 'negotiable' => false];
        }

        return [
            'price' => $request->input('price'),
            'old_price' => $request->input('old_price'),
            'negotiable' => $request->boolean('negotiable'),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductBoostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductBoostController extends Controller
{
    public function __construct(private readonly ProductBoostService $productBoostService)
    {
    }

    public function store(Product $product): RedirectResponse
    {
        try {
            $this->productBoostService->boost($product, Auth::user());

            return back()->with('success', "\"{$product->title}\" is now boosted.");
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productBoostService->unboost($product, Auth::user());

        return back()->with('success', "\"{$product->title}\" is no longer boosted.");
    }

    public function setCarouselPick(Product $product): RedirectResponse
    {
        try {
            $this->productBoostService->setCarouselPick($product, Auth::user());

            return back()->with('success', "\"{$product->title}\" will now appear in the home page carousel.");
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }
    }
}

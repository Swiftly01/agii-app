<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\StoreRealEstateListingRequest;
use App\Http\Requests\Products\UpdateRealEstateListingRequest;
use App\Models\Product;
use App\Services\Products\RealEstate\RealEstateFormSchema;
use App\Services\Products\RealEstate\RealEstateListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

final class RealEstateListingController extends Controller
{
    // $category is the slug each form route is registered with, see routes/web/product.php
    public function create(Request $request, string $category): View
    {
        $schema = RealEstateFormSchema::forCategory($category);

        return view('products.real-estate.create', [
            'schema' => $schema,
            'defaults' => $schema->defaults($request->user()),
            'user' => $request->user(),
        ]);
    }

    // The post form pre-filled with an existing listing
    public function edit(Request $request, string $slug): View
    {
        $listing = Product::realEstate()
            ->where('slug', $slug)
            ->manageableBy($request->user())
            ->firstOrFail();

        $schema = RealEstateFormSchema::forCategory($listing->category->slug);

        return view('products.real-estate.create', [
            'schema' => $schema,
            'defaults' => $schema->valuesFor($listing),
            'user' => $listing->user,
            'listing' => $listing,
        ]);
    }

    public function update(UpdateRealEstateListingRequest $request, RealEstateListingService $listings): JsonResponse
    {
        $product = $listings->update(
            $request->schema(),
            $request->product(),
            $request->listingData(),
            $request->file('images', []),
            $request->file('videos', []),
            $request->removedImages(),
            $request->removedVideos(),
        );

        return response()->json([
            'success' => true,
            'message' => 'Listing updated! Changes will be reviewed by our team before going live.',
            'redirect_url' => route('products.added', $product->id),
        ]);
    }

    public function store(StoreRealEstateListingRequest $request, RealEstateListingService $listings): JsonResponse
    {
        $product = $listings->create(
            $request->schema(),
            $request->user(),
            $request->listingData(),
            $request->file('images', []),
            $request->file('videos', []),
        );

        return response()->json([
            'success' => true,
            'message' => 'Listing submitted for review! It will appear once approved by our team.',
            'redirect_url' => route('products.added', $product->id),
        ]);
    }
}

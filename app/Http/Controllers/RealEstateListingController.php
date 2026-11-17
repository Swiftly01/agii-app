<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\StoreRealEstateListingRequest;
use App\Services\Products\RealEstate\RealEstateFormSchema;
use App\Services\Products\RealEstate\RealEstateListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

final class RealEstateListingController extends Controller
{
    public function create(Request $request, RealEstateFormSchema $schema): View
    {
        return view('products.real-estate.create', [
            'schema' => $schema,
            'defaults' => $schema->defaults($request->user()),
            'user' => $request->user(),
        ]);
    }

    public function store(StoreRealEstateListingRequest $request, RealEstateListingService $listings): JsonResponse
    {
        $product = $listings->create($request->user(), $request->validated(), $request->file('images', []));

        return response()->json([
            'success' => true,
            'message' => 'Listing submitted for review! It will appear once approved by our team.',
            'redirect_url' => route('products.added', $product->id),
        ]);
    }
}

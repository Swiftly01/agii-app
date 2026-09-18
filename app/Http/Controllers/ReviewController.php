<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please sign in as a customer to leave a review.');
        }

        if (! $user->isCustomer()) {
            return back()->with('error', 'Only customers can leave a review.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        // One review per customer per product — resubmitting the form
        // (e.g. to change their rating or fix a typo) updates it in place.
        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Thanks for your review!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Your review was removed.');
    }
}

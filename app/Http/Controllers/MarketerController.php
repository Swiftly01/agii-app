<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CommissionTransaction;
use App\Models\User;
use App\Models\Marketer;
use App\Models\MarketerWithdrawal;
use App\Models\PaymentPlan;
use App\Models\Product;
use App\Models\VendorSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\PaystackService;

class MarketerController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Show payment checkout page
     */
     
     public function checkout(PaymentPlan $plan, Request $request)
{
    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'billing_cycle' => 'required|in:monthly,yearly',
        'months' => 'required|integer|min:1|max:60',
    ]);

    $vendorId = $request->vendor_id;
    $billingCycle = $request->billing_cycle;
    $months = $request->months;

    // Get the vendor
    $vendor = Vendor::findOrFail($vendorId);
    
    // Calculate total amount with VAT
    $pricing = $plan->getPriceWithVat($billingCycle, $months);

    return view('vendor.checkout', compact('plan', 'vendor', 'billingCycle', 'months', 'pricing'));
}
    public function showCheckout($vendorId, $planId, Request $request)
    {
        $vendor = User::where('user_type', 'vendor')
            ->where('id', $vendorId)
            ->firstOrFail();

        $marketer = Auth::user()->marketer;
        if ($vendor->referred_by !== Auth::id()) {
            abort(403);
        }

        $plan = PaymentPlan::findOrFail($planId);
        
        $billingCycle = $request->get('billing_cycle', 'monthly');
        $months = $request->get('months', 1);

        // Calculate pricing
        $pricing = $plan->getPriceWithVat($billingCycle, $months);
        
        // Add commission calculation for display
        $commission = $pricing['total'] * 0.1;

        return view('marketer.vendors.checkout', compact(
            'vendor', 
            'plan', 
            'billingCycle', 
            'months', 
            'pricing',
            'commission'
        ));
    }

    /**
     * Process vendor subscription payment
     */
    public function processVendorPayment(Request $request, $vendorId)
    {
        $request->validate([
            'plan_id' => 'required|exists:payment_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'months' => 'required|integer|min:1|max:60',
        ]);

        try {
            DB::beginTransaction();

            $vendor = User::where('user_type', 'vendor')
                ->where('id', $vendorId)
                ->firstOrFail();

            $marketer = Auth::user()->marketer;

            // Verify marketer owns this vendor
            if ($vendor->referred_by !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'Unauthorized action. This vendor is not associated with your account.');
            }

            $plan = PaymentPlan::findOrFail($request->plan_id);

            // Calculate amount with VAT
            $pricing = $plan->getPriceWithVat($request->billing_cycle, $request->months);
            
            if (!isset($pricing['total_kobo'])) {
                // Convert to kobo if not already set
                $pricing['total_kobo'] = round($pricing['total'] * 100);
            }
            
            $amountInKobo = $pricing['total_kobo'];

            // Create pending subscription
            $subscription = VendorSubscription::create([
                'user_id' => $vendor->id,
                'payment_plan_id' => $plan->id,
                'billing_cycle' => $request->billing_cycle,
                'months' => $request->months,
                'amount' => $pricing['total'],
                'subtotal' => $pricing['subtotal'],
                'vat_amount' => $pricing['vat_amount'],
                'vat_rate' => $pricing['vat_rate'],
                'status' => 'pending',
                'payment_method' => 'paystack',
                'referred_by' => Auth::id(),
            ]);

            // Generate reference for Paystack
            $reference = $this->paystackService->generateReference();
            
            // Prepare Paystack payment data
            $paymentData = [
                'amount' => $amountInKobo,
                'email' => $marketer->user->email ?? Auth::user()->email, // Use marketer's email for payment
                'reference' => $reference,
                'currency' => 'NGN',
                'callback_url' => route('marketer.payment.callback') . '?reference=' . $reference,
                'metadata' => json_encode([
                    'subscription_id' => $subscription->id,
                    'plan_name' => $plan->name,
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->first_name . ' ' . $vendor->last_name,
                    'vendor_email' => $vendor->email,
                    'marketer_id' => $marketer->id,
                    'marketer_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'billing_cycle' => $request->billing_cycle,
                    'months' => $request->months,
                    'subtotal' => $pricing['subtotal'],
                    'vat_amount' => $pricing['vat_amount'],
                    'vat_rate' => $pricing['vat_rate'],
                    'total_amount' => $pricing['total'],
                    'type' => 'marketer_payment'
                ])
            ];

            // Initialize payment with Paystack
            $paymentResult = $this->paystackService->initializeTransaction($paymentData);
            
            if (!$paymentResult['success']) {
                DB::rollBack();
                
                Log::error('Paystack Initialization Failed', [
                    'error' => $paymentResult['message'] ?? 'Unknown error',
                    'data' => $paymentData
                ]);
                
                return redirect()->back()
                    ->with('error', 'Payment initialization failed: ' . ($paymentResult['message'] ?? 'Please try again.'));
            }

            // Update subscription with payment reference
            $subscription->update([
                'payment_reference' => $reference
            ]);

            DB::commit();

            // Redirect to Paystack payment page
            return redirect($paymentResult['authorization_url']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Marketer Payment Processing Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'vendor_id' => $vendorId,
                'marketer_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Payment processing failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle payment callback for marketer-initiated payments
     */
    public function handlePaymentCallback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            Log::warning('Payment callback called without reference');
            return redirect()->route('marketer.dashboard')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            // Verify transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if (!$verification['status']) {
                Log::error('Paystack verification failed', ['reference' => $reference]);
                return redirect()->route('marketer.dashboard')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

            $transaction = $verification['data'];

            // Find subscription by payment reference
            $subscription = VendorSubscription::where('payment_reference', $reference)->first();

            if (!$subscription) {
                Log::error('Subscription not found for reference: ' . $reference);
                return redirect()->route('marketer.dashboard')
                    ->with('error', 'Subscription not found. Please contact support.');
            }

            $vendor = $subscription->user;
            $marketer = Auth::user()->marketer ?? Marketer::find($subscription->referred_by);

            // Verify transaction amount matches subscription amount
            $expectedAmount = round($subscription->amount * 100); // Convert to kobo
            $actualAmount = $transaction['amount'];

            if ($expectedAmount != $actualAmount) {
                Log::error('Amount mismatch', [
                    'expected' => $expectedAmount,
                    'actual' => $actualAmount,
                    'reference' => $reference
                ]);
                
                return redirect()->route('marketer.vendors.show', $vendor->id)
                    ->with('error', 'Payment amount mismatch. Please contact support.');
            }

            if ($transaction['status'] === 'success') {
                DB::beginTransaction();

                // Update subscription details
                $subscription->update([
                    'status' => 'active',
                    'payment_method' => $transaction['channel'] ?? 'paystack',
                    'payment_details' => [
                        'gateway_response' => $transaction['gateway_response'],
                        'channel' => $transaction['channel'],
                        'ip_address' => $transaction['ip_address'],
                        'paid_at' => $transaction['paid_at'],
                        'transaction_date' => $transaction['transaction_date'],
                        'currency' => $transaction['currency'],
                        'fees' => $transaction['fees'] / 100,
                        'reference' => $transaction['reference'],
                    ],
                    'starts_at' => now(),
                    'expires_at' => $this->calculateExpiryDate($subscription->billing_cycle, $subscription->months),
                ]);

                // Calculate marketer commission (10% of subscription amount)
                $commissionRate = $marketer->commission_rate ?? 10;
                $commission = $subscription->amount * ($commissionRate / 100);

                // Update marketer earnings
                $marketer->update([
                    'pending_earnings' => ($marketer->pending_earnings ?? 0) + $commission,
                    'total_earnings' => ($marketer->total_earnings ?? 0) + $commission
                ]);

                // Log commission transaction
                CommissionTransaction::create([
                    'marketer_id' => $marketer->id,
                    'vendor_id' => $vendor->id,
                    'subscription_id' => $subscription->id,
                    'amount' => $subscription->amount,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commission,
                    'status' => 'pending',
                    'payment_reference' => $reference,
                ]);

                DB::commit();

                // Send notification or email
                // $this->sendPaymentSuccessNotification($vendor, $subscription, $marketer);

                return redirect()->route('marketer.vendors.show', $vendor->id)
                    ->with('success', 'Payment successful! Vendor account is now active. You earned ₦' . number_format($commission, 2) . ' commission.');
                    
            } else {
                // Payment failed
                $subscription->update([
                    'status' => 'failed',
                    'payment_details' => [
                        'gateway_response' => $transaction['gateway_response'],
                        'message' => $transaction['message'],
                        'reference' => $reference,
                    ]
                ]);

                $errorMessage = $transaction['gateway_response'] ?? ($transaction['message'] ?? 'Payment failed');

                return redirect()->route('marketer.vendors.payment', $vendor->id)
                    ->with('error', 'Payment failed: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payment Callback Error: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('marketer.dashboard')
                ->with('error', 'Payment processing error. Please contact support with reference: ' . $reference);
        }
    }

    /**
     * Calculate subscription expiry date
     */
    private function calculateExpiryDate($billingCycle, $months)
    {
        if ($billingCycle === 'yearly') {
            return now()->addYears(ceil($months / 12));
        }

        return now()->addMonths($months);
    }

    
    
     public function dashboard()
    {
        $user = Auth::user();

        if (!$user->isMarketer()) {
            return redirect()->route('home')->with('error', 'Access denied. Marketer account required.');
        }

        $marketer = $user->marketer;

        // Get recent vendors (last 10)
        $recentVendors = User::where('referred_by', Auth::id())
            ->where('user_type', 'vendor')
            ->with(['store', 'activeSubscription'])
            ->withCount('products')
            ->latest()
            ->take(10)
            ->get();

        // Get recent commissions
        $recentCommissions = CommissionTransaction::where('marketer_id', $marketer->id)
            ->with(['vendor', 'subscription'])
            ->latest()
            ->take(10)
            ->get();


        $vendors = User::where('user_type', 'vendor')
            ->where('referred_by', $user->id)
            ->get();

        // Calculate statistics
        $stats = [
            'total_vendors' => $vendors->count(),
            'active_vendors' => $vendors->where('activeSubscription', '!=', null)->count(),
            'total_earnings' => $marketer->total_earnings,
            'pending_earnings' => $marketer->pending_earnings,
            'paid_earnings' => $marketer->paid_earnings,
            'total_products' => $marketer->getTotalProductsAttribute(),
            'total_sales' => $marketer->getTotalSalesAttribute(),
        ];

        return view('marketer.dashboard', compact('marketer', 'recentVendors', 'recentCommissions', 'stats'));
    }
    
     public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
        ]);

        $marketer = Auth::user()->marketer;

        if ($marketer->pending_earnings < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient pending earnings.'
            ]);
        }

        try {
            // Create withdrawal request
            $withdrawal = MarketerWithdrawal::create([
                'marketer_id' => $marketer->id,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'status' => 'pending',
            ]);


            // Update marketer's pending earnings
            $marketer->update([
                'pending_earnings' => $marketer->pending_earnings - $request->amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request submitted successfully. It will be processed within 24-48 hours.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Withdrawal request failed. Please try again.'
            ]);
        }
    }
    public function createVendor()
    {
        return view('marketer.vendors.create');
    }
    
    public function storeVendor(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'business_category' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'local_government' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);



        try {
            $marketer = Auth::user()->marketer;

            // Generate unique referral code for vendor
            $vendorReferralCode = strtoupper(Str::random(8));

            $vendor = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => 'vendor',
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'business_category' => $request->business_category,
                'state' => $request->state,
                'local_government' => $request->local_government,
                'city' => $request->city,
                'address' => $request->address,
                'referral_code' => $vendorReferralCode,
                'referred_by' => Auth::id(),
                'email_verified_at' => now(), // Auto-verify since marketer is adding
                'terms_accepted' => true,
            ]);
            // dd();
            return redirect()->route('marketer.vendors.payment', $vendor->id)
                ->with('success', 'Vendor created successfully! Please complete payment setup.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create vendor: ' . $e->getMessage());
        }
    }


     public function showVendorPayment($vendorId)
    {
        $vendor = User::where('user_type', 'vendor')
            ->where('id', $vendorId)
            ->firstOrFail();

        // Verify marketer owns this vendor
        $marketer = Auth::user()->marketer;
        if ($vendor->referred_by !== Auth::id()) {
            abort(403);
        }

        // Get available payment plans
        $paymentPlans = PaymentPlan::where('is_active', true)->get();

        return view('marketer.vendors.payment', compact('vendor', 'paymentPlans'));
    }
    
    public function createProduct($vendorId)
    {
        $vendor = User::where('user_type', 'vendor')
            ->where('id', $vendorId)
            ->firstOrFail();

        $marketer = Auth::user()->marketer;
        if ($vendor->referred_by !== Auth::id()) {
            abort(403);
        }

        $categories = \App\Models\Category::all();

        return view('marketer.products.create', compact('vendor', 'categories'));
    }
    
    
       public function storeProduct(Request $request, $vendorId)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'old_price' => 'nullable|numeric|min:0',
                'condition' => 'required|in:new,used_like_new,used_good,used_fair',
                'location' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'images' => 'required|array|min:1|max:10',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'specifications' => 'nullable|array',
                'negotiable' => 'boolean',
            ]);

            $vendor = User::where('user_type', 'vendor')
                ->where('id', $vendorId)
                ->firstOrFail();

            $marketer = Auth::user()->marketer;
            if ($vendor->referred_by !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $directory = public_path('product-images');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($directory, $filename);
                    $imagePaths[] = 'product-images/' . $filename;
                }
            }

            // Generate slug
            $slug = $this->generateUniqueSlug($request->title);

            // Handle specifications
            $specifications = [];
            if ($request->has('specifications') && is_array($request->specifications)) {
                $specifications = array_filter($request->specifications, function ($value) {
                    return !empty($value) && $value !== '';
                });
            }

            $storeId = $vendor->store ? $vendor->store->id : null;

            // Create product
            $product = Product::create([
                'user_id' => $vendor->id,
                'store_id' => $storeId,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'price' => $request->price,
                'old_price' => $request->old_price,
                'condition' => $request->condition,
                'location' => $request->location,
                'quantity' => $request->quantity,
                'images' => $imagePaths,
                'specifications' => $specifications,
                'negotiable' => $request->boolean('negotiable'),
                'tags' => $request->tags ?? [],
                'status' => 'active',
                'meta_title' => $request->title,
                'meta_description' => Str::limit($request->description, 160),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'redirect_url' => route('marketer.vendors.show', $vendor->id)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }
 public function showVendor($vendorId)
    {
        // $vendor = User::where('user_type', 'vendor')
        //     ->where('id', $vendorId)
        //     ->with('store', 'activeSubscription', 'products')
        //     ->firstOrFail();

        $vendor = User::where('user_type', 'vendor')
            ->where('id', $vendorId)
            ->with([
                'store',
                'products',
                'activeSubscription.paymentPlan' // ðŸ‘ˆ Add this
            ])
            ->firstOrFail();


        $marketer = Auth::user()->marketer;
        if ($vendor->referred_by !== Auth::id()) {
            abort(403);
        }

        return view('marketer.vendors.show', compact('vendor'));
    }

private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
    
    public function showProduct(Product $product)
    {

        try {
            // Get the vendor associated with this product
            $vendor = $product->user;

            // Verify that the current marketer has access to this vendor
            // $marketer = Auth::user()->marketer;
            // if ($vendor->referred_by !== Auth::id() || Auth::user()->user_type !== 'admin') {
            //     abort(403, 'Unauthorized action');
            // }

            $categories = Category::all();



            return view('marketer.products.show-edit', compact('product', 'vendor', 'categories'));
        } catch (\Exception $e) {


            dd($e->getMessage());

            return redirect()->back()->with('error', 'Product not found: ' . $e->getMessage());
        }
    }
    
    public function updateProduct(Request $request, Product $product)
    {
        try {
            // Verify ownership
            $marketer = Auth::user()->marketer;
            if ($product->user->referred_by !== Auth::id() || Auth::user()->user_type !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'old_price' => 'nullable|numeric|min:0',
                'condition' => 'required|in:new,used_like_new,used_good,used_fair',
                'location' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'removed_images.*' => 'nullable|integer',
                'negotiable' => 'boolean',
            ]);

            // Handle image updates
            $currentImages = $product->images ?? [];

            // Remove specified images
            if ($request->has('removed_images')) {
                foreach ($request->removed_images as $index) {
                    if (isset($currentImages[$index])) {
                        // Optional: Delete the file from storage
                        // Storage::delete($currentImages[$index]);
                        unset($currentImages[$index]);
                    }
                }
                // Reindex array
                $currentImages = array_values($currentImages);
            }

            // Add new images
            $newImagePaths = [];
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $directory = public_path('product-images');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($directory, $filename);
                    $newImagePaths[] = 'product-images/' . $filename;
                }
            }

            $allImages = array_merge($currentImages, $newImagePaths);

            // Handle specifications
            $specifications = [];
            if ($request->has('specifications_keys')) {
                foreach ($request->specifications_keys as $index => $key) {
                    $value = $request->specifications_values[$index] ?? '';
                    if (!empty($key) && !empty($value)) {
                        $specifications[$key] = $value;
                    }
                }
            }

            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'old_price' => $request->old_price,
                'condition' => $request->condition,
                'location' => $request->location,
                'quantity' => $request->quantity,
                'images' => $allImages,
                'specifications' => $specifications,
                'negotiable' => $request->boolean('negotiable'),
                'meta_title' => $request->title,
                'meta_description' => Str::limit($request->description, 160),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }
    
      public function showVendorList()
    {
        $user = Auth::user();

        // Get vendors based on user type
        if ($user->user_type === 'marketer') {
            // Get vendors referred by this marketer
            $vendors = User::where('user_type', 'vendor')
                ->where('referred_by', $user->id)
                ->withCount('products')
                ->with('activeSubscription')
                ->latest()
                ->get();
        } else {
            // Admin or other user types - get all vendors
            $vendors = User::where('user_type', 'vendor')
                ->withCount('products')
                ->with('activeSubscription')
                ->latest()
                ->get();
        }

        $stats = [
            'total_vendors' => $vendors->count(),
            'active_vendors' => $vendors->where('activeSubscription', '!=', null)->count(),
            'total_products' => $vendors->sum('products_count'),
        ];


        session(['vendor_stats' => $stats]);

        return view('marketer.vendors.showvendor', compact('vendors', 'stats'));
    }
    
     public function approve(Product $product)
    {
        // Check if user has permission to approve this product
        $marketer = Auth::user()->marketer;
        // if ($product->user->referred_by !== Auth::id()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized action'
        //     ], 403);
        // }

        $product->update([
            'status' => 'active',

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product approved successfully!'
        ]);
    }

    public function disapprove(Product $product)
    {
        // Check if user has permission to disapprove this product
        // $marketer = Auth::user()->marketer;
        // if ($product->user->referred_by !== Auth::id()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized action'
        //     ], 403);
        // }

        $product->update([
            'status' => 'inactive',

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product disapproved successfully!'
        ]);
    }
    
}
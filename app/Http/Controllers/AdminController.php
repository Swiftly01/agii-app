<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Task;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    /**
     * Display login page
     */
    public function index()
    {
        // dd('Admin Controller');
        return view('admin.index');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalVendors' => User::where('user_type', 'vendor')->count(),
            'totalCustomers' => User::where('user_type', 'customer')->count(),
            'totalMarketers' => User::where('user_type', 'marketer')->count(),
            'pendingProducts' => Product::where('status', 'pending')->count(),
            // 'totalTransactions' => Transaction::sum('amount'),
        ];


        $recentUsers = User::latest()->take(5)->get();
        return view('admin.index', compact('stats', 'recentUsers'));
    }

    /**
     * Show all vendors with pagination and search
     */

    /*
    public function showVendors(Request $request)
    {
        $query = User::where('user_type', 'vendor');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('business_name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->whereHas('products', function ($q) {
                    $q->where('status', 'active');
                });
            } elseif ($request->status == 'inactive') {
                $query->whereDoesntHave('products', function ($q) {
                    $q->where('status', 'active');
                });
            }
        }

        // Filter by registration date


        if ($request->filled('date_from')) {
            $from = Carbon::parse($request->date_from)->startOfDay();
            $query->where('created_at', '>=', $from);
        }

        if ($request->filled('date_to')) {
            $to = Carbon::parse($request->date_to)->endOfDay();
            $query->where('created_at', '<=', $to);
        }


        $vendors = $query->withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])
            ->withSum('products', 'views')
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->except('page'));

        return view('admin.vendors.index', compact('vendors'));
    }
        */

    /**
     * Show vendor details with their products
     */
    /*
    public function showVendor($id)
    {
        $vendor = User::with(['products' => function ($query) {
            $query->with('category')
                ->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Get vendor stats
        $vendorStats = [
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('status', 'active')->count(),
            'pending_products' => $vendor->products()->where('status', 'pending')->count(),
            'total_views' => $vendor->products()->sum('views'),
            'total_sales' => $vendor->products()->where('status', 'sold')->count(),
            'average_rating' => $vendor->products()->avg('rating'),
        ];

        return view('admin.vendors.show', compact('vendor', 'vendorStats'));
    }
        */

      public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->featured = ! $product->featured;
        $product->save();

        return back()->with(
            'success',
            $product->featured ? "\"{$product->title}\" is now featured." : "\"{$product->title}\" is no longer featured."
        );
    }

    /**
     * Approve or reject products
     */
    public function approveProduct(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,rejected',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $product = Product::findOrFail($id);

        $oldStatus = $product->status;
        $product->status = $request->status;

        // Store rejection reason if provided
        if ($request->has('rejection_reason') && $request->status == 'rejected') {
            $product->rejection_reason = $request->rejection_reason;
            $product->reviewed_at = now();
            $product->reviewed_by = Auth::id();
        }

        $product->save();

        // Send notification to vendor about status change
        if ($oldStatus != $product->status) {
            // You can implement notification logic here
            // Example: Mail::to($product->user->email)->send(new ProductStatusChanged($product));
        }


        // return redirect()->back()->with('success', 'Product status updated successfully.');
        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully.',
            'status' => $product->status,
            'status_label' => ucfirst($product->status)
        ]);
    }

    /**
     * Bulk approve/reject products
     */
    public function bulkApproveProducts(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:approve,reject'
        ]);

        $status = $request->action == 'approve' ? 'active' : 'rejected';

        Product::whereIn('id', $request->product_ids)
            ->update([
                'status' => $status,
                // 'reviewed_at' => now(),
                // 'reviewed_by' => Auth::id()
            ]);

        return response()->json([
            'success' => true,
            'message' => "{$request->action} {$request->product_ids} products successfully."
        ]);
    }

    /**
     * Show products for approval (pending products)
     */
    public function productsForApproval(Request $request)
    {
        $query = Product::with(['user', 'category']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Filter by date_from
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter by date_to
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply order + paginate
        $products = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));

        $categories = Category::all();

        return view('admin.products.pending', compact('products', 'categories'));
    }



    public function getPendingProducts(Request $request)
    {
        // $query = Product::where('status', 'pending')
        //     ->with(['user', 'category']);


        $query = Product::whereIn('status', ['pending', 'inactive']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Filter by date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->except('page'));

        $categories = Category::all(); // Assuming you have a Category model

        return view('admin.products.pending', compact('products', 'categories'));
    }



    /**
     * Show all customers
     */
    public function showCustomers()
    {
        $customers = User::where('user_type', 'customer')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed'],
            'user_type' => 'required|in:admin,vendor,customer,marketer',
            'state' => 'nullable|string|max:255',
            'local_government' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'business_name' => 'required_if:user_type,vendor|nullable|string|max:255',
            'business_category' => 'required_if:user_type,vendor|nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $userData = $request->except('profile_image');
        $userData['password'] = Hash::make($request->password);
        $userData['terms_accepted'] = true;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $directory = public_path('profile-images');

            // Create directory if it doesn't exist
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move image to directory
            $image->move($directory, $filename);

            // Store relative path in userData
            $userData['profile_image'] = 'profile-images/' . $filename;
        }

        $user = User::create($userData);

        event(new Registered($user));

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Edit user profile
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'user_type' => 'required|in:admin,vendor,customer,marketer',
            'state' => 'nullable|string|max:255',
            'local_government' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'business_name' => 'required_if:user_type,vendor|nullable|string|max:255',
            'business_category' => 'required_if:user_type,vendor|nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $userData = $request->except('profile_image');

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $userData['profile_image'] = $path;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user (soft delete)
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Show all users
     */




    /**
     * Show all users with search and filters
     */
    public function showUsers(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('business_name', 'like', "%$search%");
            });
        }

        // Filter by user type
        if ($request->has('type')) {
            $query->where('user_type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status == 'unverified') {
                $query->whereNull('email_verified_at');
            } elseif ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by date
        if (!empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }


        $users = $query->latest()
            ->paginate(50)
            ->appends($request->except('page'));

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show individual user details
     */
    public function showUser($id)
    {
        $user = User::withCount(['products', 'marketercommissionTransactions'])
            ->findOrFail($id);




        return view('admin.users.show', compact('user'));
    }

    /**
     * Bulk assign role
     */
    public function bulkAssignRole(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'user_type' => 'required|in:admin,vendor,customer,marketer'
        ]);

        User::whereIn('id', $request->user_ids)
            ->update(['user_type' => $request->user_type]);

        return response()->json([
            'success' => true,
            'message' => 'Roles updated for ' . count($request->user_ids) . ' users.'
        ]);
    }

    /**
     * Bulk verify email
     */
    public function bulkVerifyEmail(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        User::whereIn('id', $request->user_ids)
            ->update(['email_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified for ' . count($request->user_ids) . ' users.'
        ]);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        User::whereIn('id', $request->user_ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->user_ids) . ' users deleted successfully.'
        ]);
    }

    /**
     * Verify individual user email
     */
    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * Assign roles to user
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'user_type' => 'required|in:admin,marketer,customer,vendor'
        ]);

        $user = User::findOrFail($id);
        $user->user_type = $request->user_type;
        $user->save();

        if ($request->user_type === 'marketer') {

            // Prevent duplicate marketer records
            $marketer = Marketer::where('user_id', $user->id)->first();

            if (!$marketer) {
                Marketer::create([
                    'user_id' => $user->id,
                    'referral_code' => $this->generateReferralCode($user),
                    'commission_rate' => 10.00,
                    'total_earnings' => 0,
                    'pending_earnings' => 0,
                    'paid_earnings' => 0,
                    'is_active' => 1,
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'User role updated successfully.');
    }

    private function generateReferralCode($user)
    {
        // Get first name safely
        $firstName = trim(explode(' ', $user->first_name ?? $user->name)[0]);

        // Take first 3 letters, uppercase
        $prefix = strtoupper(Str::substr($firstName, 0, 3));

        do {
            // Random 4-digit number
            $randomNumber = rand(1000, 9999);

            $code = $prefix . '-' . $randomNumber;
        } while (Marketer::where('referral_code', $code)->exists());

        return $code;
    }




    /**
     * Get all transactions
     */
    // public function showTransactions()
    // {
    //     $transactions = Transaction::with('user')
    //         ->latest()
    //         ->paginate(20);

    //     return view('admin.transactions.index', compact('transactions'));
    // }

    // /**
    //  * Show create task form
    //  */
    // public function createTask()
    // {
    //     $marketers = User::where('user_type', 'marketer')->get();
    //     return view('admin.tasks.create', compact('marketers'));
    // }



    /**
     * Show create task form (supports multiple tasks)
     */
    public function createTask()
    {
        $marketers = User::where('user_type', 'marketer')
            ->with(['staffProfile' => function ($query) {
                $query->select('id', 'user_id', 'staff_id', 'department');
            }])
            ->withCount(['assignedTasks' => function ($query) {
                $query->where('status', '!=', 'completed');
            }])
            ->get();

        // Transform the data to include staff info
        $marketers->transform(function ($marketer) {
            $marketer->staff_number = $marketer->staffProfile ? $marketer->staffProfile->staff_id : 'N/A';
            $marketer->department = $marketer->staffProfile ? $marketer->staffProfile->department : 'N/A';
            return $marketer;
        });

        // Default number of task rows to show
        $defaultTaskCount = 1;

        return view('admin.tasks.create', compact('marketers', 'defaultTaskCount'));
    }



    /**
     * Store new task
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'marketer_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'required|date',
        ]);

        Task::create([
            'marketer_id' => $request->marketer_id,
            'assigned_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task assigned successfully.');
    }


    /**
     * Store multiple tasks
     */
    // public function storeMultiple(Request $request)
    // {
    //     $request->validate([
    //         'marketer_id' => 'required|exists:users,id',
    //         'description' => 'required|string',
    //         'tasks' => 'required|array|min:1',
    //         'report_to' => 'required|array|min:1',
    //         'tasks.*.title' => 'required|string|max:255',
    //         'tasks.*.priority' => 'required|in:low,medium,high',
    //         'tasks.*.deadline' => 'required|date',
    //         'tasks.*.target_leads' => 'nullable|integer|min:0',
    //         'target_conversion' => 'nullable|numeric|min:0|max:100',
    //     ]);

    //     $createdTasks = [];

    //     foreach ($request->tasks as $taskData) {
    //         $task = Task::create([
    //             'marketer_id' => $request->marketer_id,
    //             'assigned_by' => Auth::id(),
    //             'report_to' => $request->report_to,
    //             'title' => $taskData['title'],
    //             'description' => $request->description, // Same description for all tasks
    //             'priority' => $taskData['priority'],
    //             'deadline' => $taskData['deadline'],
    //             'target_leads' => $taskData['target_leads'] ?? null,
    //             'target_conversion' => $request->target_conversion,
    //             'status' => 'pending',
    //             'progress' => 0,
    //         ]);

    //         $createdTasks[] = $task;
    //     }

    //     // Handle file upload if present
    //     if ($request->hasFile('attached_file')) {
    //         $file = $request->file('attached_file');
    //         // Store file and associate with tasks (you might need a task_files table)
    //         // This depends on your file storage requirements
    //     }

    //     return redirect()->route('admin.tasks.index')
    //         ->with('success', count($createdTasks) . ' task(s) assigned successfully to ' . User::find($request->marketer_id)->first_name);
    // }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'marketer_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'tasks' => 'required|array|min:1',
            'report_to' => 'required|string',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.priority' => 'required|in:low,medium,high',
            'tasks.*.deadline' => 'required|date',
            'tasks.*.target_leads' => 'nullable|integer|min:0',
            'target_conversion' => 'nullable|numeric|min:0|max:100',
            'attached_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // Added validation
        ]);

        $createdTasks = [];
        $attachmentPath = null;

        // Handle file upload if present - save to public directory
        if ($request->hasFile('attached_file') && $request->file('attached_file')->isValid()) {
            $file = $request->file('attached_file');

            // Generate a unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move file to public/task-attachments directory
            $file->move(public_path('task-attachments'), $fileName);

            // Store the path relative to public directory
            $attachmentPath = 'task-attachments/' . $fileName;
        }

        foreach ($request->tasks as $taskData) {
            $task = Task::create([
                'marketer_id' => $request->marketer_id,
                'assigned_by' => Auth::id(),
                'assignby_name' => $request->assigned_by,
                'report_to' => $request->report_to,
                'title' => $taskData['title'],
                'description' => $request->description,
                'priority' => $taskData['priority'],
                'deadline' => $taskData['deadline'],
                'target_leads' => $taskData['target_leads'] ?? null,
                'target_conversion' => $request->target_conversion,
                'status' => 'pending',
                'progress' => 0,
                'attachment' => $attachmentPath, // Add this column to your tasks table
            ]);

            $createdTasks[] = $task;
        }

        $marketer = User::find($request->marketer_id);

        $message = count($createdTasks) . ' task(s) assigned successfully to ' . $marketer->first_name;
        if ($attachmentPath) {
            $message .= ' with attachment';
        }

        return redirect()->route('admin.tasks.index')
            ->with('success', $message);
    }


    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        $task = Task::with(['marketer', 'assigner'])->findOrFail($id);

        $marketers = User::where('user_type', 'marketer')
            ->withCount(['assignedTasks' => function ($query) {
                $query->where('status', '!=', 'completed');
            }])
            ->get();

        return view('admin.tasks.edit', compact('task', 'marketers'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'marketer_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'progress' => 'required|integer|min:0|max:100',
            'deadline' => 'nullable|date',
            'target_leads' => 'nullable|integer|min:0',
            'target_conversion' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);

        // Prepare update data
        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'progress' => $request->progress,
            'target_leads' => $request->target_leads,
            'target_conversion' => $request->target_conversion,
        ];

        // Update marketer if changed
        if ($request->has('marketer_id') && $request->marketer_id != $task->marketer_id) {
            $updateData['marketer_id'] = $request->marketer_id;
        }

        // Update deadline if provided
        if ($request->filled('deadline')) {
            $updateData['deadline'] = $request->deadline;
        }

        // Append notes if provided
        if ($request->filled('notes')) {
            $timestamp = now()->format('Y-m-d H:i');
            $updateNotes = "Update [" . $timestamp . "]: " . $request->notes;

            $updateData['notes'] = $task->notes
                ? $task->notes . "\n\n" . $updateNotes
                : $updateNotes;
        }

        $task->update($updateData);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $task = Task::with(['marketer', 'assigner'])->findOrFail($id);

        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show tasks with filters
     */
    //  public function showTasks(Request $request)
    // {
    //     $query = Task::with(['marketer', 'assigner']);

    //     // Search functionality
    //     if ($request->has('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('title', 'like', "%$search%")
    //                 ->orWhere('description', 'like', "%$search%")
    //                 ->orWhere('notes', 'like', "%$search%");
    //         });
    //     }

    //     // Filter by status
    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     // Filter by priority
    //     if ($request->has('priority')) {
    //         $query->where('priority', $request->priority);
    //     }

    //     // Filter by marketer
    //     if ($request->has('marketer')) {
    //         $query->where('marketer_id', $request->marketer);
    //     }

    //     // Calculate stats
    //     $stats = [
    //         'total' => Task::count(),
    //         'pending' => Task::where('status', 'pending')->count(),
    //         'in_progress' => Task::where('status', 'in_progress')->count(),
    //         'completed' => Task::where('status', 'completed')->count(),
    //     ];

    //     $tasks = $query->latest()
    //         ->paginate(50)
    //         ->appends($request->except('page'));

    //     // Add is_overdue attribute
    //     $tasks->transform(function ($task) {
    //         $task->is_overdue = $task->deadline < now() && $task->status != 'completed';
    //         return $task;
    //     });

    //     $marketers = User::where('user_type', 'marketer')->get();

    //     return view('admin.tasks.index', compact('tasks', 'stats', 'marketers'));
    // }


    public function showTasks(Request $request)
    {
        $query = Task::with(['marketer', 'assigner']);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Marketer filter
        if ($request->filled('marketer')) {
            $query->where('marketer_id', $request->marketer);
        }

        // Stats
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];

        $tasks = $query->latest()
            ->paginate(50)
            ->appends($request->except('page'));

        $tasks->transform(function ($task) {
            $task->is_overdue = $task->deadline < now() && $task->status !== 'completed';
            return $task;
        });

        $marketers = User::where('user_type', 'marketer')->get();

        return view('admin.tasks.index', compact('tasks', 'stats', 'marketers'));
    }


    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'progress' => 'nullable|integer|min:0|max:100',
            'status_notes' => 'nullable|string|max:500'
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'status' => $request->status,
            'progress' => $request->progress ?? $task->progress,
            'notes' => $request->filled('status_notes')
                ? ($task->notes ? $task->notes . "\n\nStatus Update [" . now()->format('Y-m-d H:i') . "]: " . $request->status_notes
                    : "Status Update [" . now()->format('Y-m-d H:i') . "]: " . $request->status_notes)
                : $task->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.'
        ]);
    }


    /**
     * Update task progress (Simplified version)
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'progress' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $task = Task::where('marketer_id', Auth::id())
            ->findOrFail($request->task_id);

        // Update progress
        $task->progress = $request->progress;

        // Auto-update status
        if ($request->progress == 100) {
            $task->status = 'completed';
        } elseif ($request->progress > 0 && $task->status == 'pending') {
            $task->status = 'in_progress';
        }

        // Add note if provided
        if ($request->filled('notes')) {
            $timestamp = now()->format('Y-m-d H:i');
            $note = "Progress Update [$timestamp]: {$request->progress}%\n{$request->notes}";

            $task->notes = $task->notes
                ? $task->notes . "\n\n" . $note
                : $note;
        }

        $task->save();

        // AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Progress updated',
                'progress' => $task->progress,
                'status' => $task->status
            ]);
        }

        // Form submission response
        return back()->with('success', 'Progress updated successfully');
    }

    /**
     * Get task details for marketer view
     */

    /**
     * Show task details for marketer
     */
    public function marketerTaskDetail($id)
    {
        $user = Auth::user();

        // Ensure only marketers can access this
        if ($user->user_type !== 'marketer') {
            abort(403, 'Unauthorized access');
        }

        $task = Task::with(['assigner', 'marketer'])
            ->where('marketer_id', $user->id)
            ->findOrFail($id);

        // Calculate additional information
        $task->is_overdue = $task->deadline < now() && $task->status != 'completed';
        $task->days_remaining = now()->diffInDays($task->deadline, false);
        $task->days_remaining_text = $task->days_remaining == 0 ? 'Today' : ($task->days_remaining > 0 ? $task->days_remaining . ' days left' :
                abs($task->days_remaining) . ' days ago');

        // Parse notes into array for display
        $notes = $task->notes ? array_filter(explode("\n\n", $task->notes)) : [];

        return view('marketer.tasks.show', compact('task', 'notes'));
    }


    /**
     * Add a note to a task (for marketer)
     */
    public function addNote(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $task = Task::where('marketer_id', $user->id)->findOrFail($id);

        $timestamp = now()->format('Y-m-d H:i');
        $newNote = "Note [" . $timestamp . "]: " . $request->note;

        $task->update([
            'notes' => $task->notes
                ? $task->notes . "\n\n" . $newNote
                : $newNote
        ]);

        return redirect()->route('marketer.tasks.show', $task->id)
            ->with('success', 'Note added successfully.');
    }

    /**
     * Update task status (for marketer - form submission)
     */
    public function updateTaskStatusMarketer(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'progress' => 'required|integer|min:0|max:100',
            'status_notes' => 'nullable|string|max:500'
        ]);

        $task = Task::where('marketer_id', $user->id)->findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'progress' => $request->progress,
        ];

        // Add status notes
        if ($request->filled('status_notes')) {
            $timestamp = now()->format('Y-m-d H:i');
            $statusNote = "Status Update [" . $timestamp . "]: " . $request->status_notes;

            $updateData['notes'] = $task->notes
                ? $task->notes . "\n\n" . $statusNote
                : $statusNote;
        }

        $task->update($updateData);

        return redirect()->route('marketer.tasks.show', $task->id)
            ->with('success', 'Task status updated successfully.');
    }







    /**
     * Display tasks for the authenticated marketer
     */
    public function marketerTasks(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // If user is admin, redirect to admin tasks view
        if ($user->user_type === 'admin') {
            return redirect()->route('admin.tasks.index');
        }

        // Ensure only marketers can access this
        if ($user->user_type !== 'marketer') {
            abort(403, 'Unauthorized access');
        }

        // Start query for tasks assigned to this marketer
        $query = Task::where('marketer_id', $user->id)
            ->with(['assigner' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'profile_image');
            }])
            ->select(
                'id',
                'title',
                'description',
                'priority',
                'status',
                'progress',
                'deadline',
                'marketer_id',
                'assigned_by',
                'created_at',
                'updated_at',
                'target_leads',
                'target_conversion',
                'notes',
                'attachment'
            ); // Added 'attachment' here

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('notes', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('deadline', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('deadline', '<=', $request->date_to);
        }

        // Filter by overdue tasks
        if ($request->has('overdue') && $request->overdue == '1') {
            $query->where('deadline', '<', now())
                ->where('status', '!=', 'completed');
        }

        // Filter by upcoming tasks (deadline within next 7 days)
        if ($request->has('upcoming') && $request->upcoming == '1') {
            $query->where('deadline', '>', now())
                ->where('deadline', '<=', now()->addDays(7))
                ->where('status', '!=', 'completed');
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'deadline'); // Default sort by deadline
        $sortOrder = $request->get('sort_order', 'asc'); // Default ascending

        $validSortColumns = ['deadline', 'created_at', 'updated_at', 'priority', 'status', 'progress'];
        $validSortOrders = ['asc', 'desc'];

        if (in_array($sortBy, $validSortColumns) && in_array($sortOrder, $validSortOrders)) {
            // Handle priority custom sorting (high > medium > low)
            if ($sortBy === 'priority') {
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low') $sortOrder");
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            // Default ordering: overdue first, then by deadline
            $query->orderByRaw("
            CASE 
                WHEN deadline < NOW() AND status != 'completed' THEN 1
                WHEN deadline >= NOW() AND status != 'completed' THEN 2
                ELSE 3
            END
        ")
                ->orderBy('deadline', 'asc');
        }

        // Get paginated results
        $perPage = $request->get('per_page', 50);
        $tasks = $query->paginate($perPage)->appends($request->except('page'));

        // Calculate statistics for this marketer
        $stats = $this->getMarketerStats($user->id);

        // Add additional attributes to each task
        $tasks->getCollection()->transform(function ($task) {
            $task->is_overdue = $task->deadline < now() && $task->status != 'completed';

            // Calculate days remaining
            $now = now();
            $deadline = Carbon::parse($task->deadline);
            $task->days_remaining = $now->diffInDays($deadline, false);

            if ($task->days_remaining === 0) {
                $task->days_remaining_text = 'Today';
            } elseif ($task->days_remaining > 0) {
                $task->days_remaining_text = $task->days_remaining . ' day' . ($task->days_remaining > 1 ? 's' : '') . ' left';
            } else {
                $task->days_remaining_text = abs($task->days_remaining) . ' day' . (abs($task->days_remaining) > 1 ? 's' : '') . ' ago';
            }

            // Calculate time remaining in hours if less than 1 day
            if (abs($task->days_remaining) === 0) {
                $hoursRemaining = $now->diffInHours($deadline, false);
                if (abs($hoursRemaining) <= 24) {
                    if ($hoursRemaining === 0) {
                        $task->days_remaining_text = 'Due now';
                    } elseif ($hoursRemaining > 0) {
                        $task->days_remaining_text = $hoursRemaining . ' hour' . ($hoursRemaining > 1 ? 's' : '') . ' left';
                    } else {
                        $task->days_remaining_text = abs($hoursRemaining) . ' hour' . (abs($hoursRemaining) > 1 ? 's' : '') . ' ago';
                    }
                }
            }

            // Get first name from assigner
            if ($task->assigner) {
                $task->assigner_name = $task->assigner->first_name . ' ' . $task->assigner->last_name;
            } else {
                $task->assigner_name = 'N/A';
            }

            // Truncate description for display
            $task->short_description = Str::limit($task->description, 100);

            // Add attachment info
            if ($task->attachment) {
                $task->has_attachment = true;
                $task->attachment_url = asset($task->attachment);
                $task->attachment_name = basename($task->attachment);
                $task->attachment_extension = pathinfo($task->attachment, PATHINFO_EXTENSION);
                $task->attachment_size = file_exists(public_path($task->attachment)) ?
                    filesize(public_path($task->attachment)) : 0;

                // Format file size
                if ($task->attachment_size > 0) {
                    $units = ['B', 'KB', 'MB', 'GB'];
                    $size = $task->attachment_size;
                    $i = 0;
                    while ($size >= 1024 && $i < 3) {
                        $size /= 1024;
                        $i++;
                    }
                    $task->attachment_size_formatted = round($size, 2) . ' ' . $units[$i];
                } else {
                    $task->attachment_size_formatted = 'Unknown';
                }
            } else {
                $task->has_attachment = false;
            }

            return $task;
        });

        // Get filter counts for UI
        $filterCounts = [
            'pending' => Task::where('marketer_id', $user->id)->where('status', 'pending')->count(),
            'in_progress' => Task::where('marketer_id', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('marketer_id', $user->id)->where('status', 'completed')->count(),
            'overdue' => Task::where('marketer_id', $user->id)
                ->where('deadline', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
            'upcoming' => Task::where('marketer_id', $user->id)
                ->where('deadline', '>', now())
                ->where('deadline', '<=', now()->addDays(7))
                ->where('status', '!=', 'completed')
                ->count(),
        ];

        // Get priority counts
        $priorityCounts = [
            'high' => Task::where('marketer_id', $user->id)->where('priority', 'high')->count(),
            'medium' => Task::where('marketer_id', $user->id)->where('priority', 'medium')->count(),
            'low' => Task::where('marketer_id', $user->id)->where('priority', 'low')->count(),
        ];

        // Get attachment stats
        $attachmentStats = [
            'total_with_attachments' => Task::where('marketer_id', $user->id)
                ->whereNotNull('attachment')
                ->count(),
        ];

        // Prepare date range for filter (default: last 30 days to next 30 days)
        $defaultDateFrom = now()->subDays(30)->format('Y-m-d');
        $defaultDateTo = now()->addDays(30)->format('Y-m-d');

        return view('marketer.tasks.index', compact(
            'tasks',
            'stats',
            'user',
            'filterCounts',
            'priorityCounts',
            'attachmentStats',
            'defaultDateFrom',
            'defaultDateTo'
        ));
    }
    /**
     * Get statistics for a specific marketer
     */
    private function getMarketerStats($marketerId)
    {
        $totalTasks = Task::where('marketer_id', $marketerId)->count();

        $completedTasks = Task::where('marketer_id', $marketerId)
            ->where('status', 'completed')
            ->count();

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $overdueTasks = Task::where('marketer_id', $marketerId)
            ->where('deadline', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        // Calculate average progress of all tasks
        $averageProgress = Task::where('marketer_id', $marketerId)
            ->where('status', '!=', 'completed')
            ->avg('progress');

        // Get tasks due this week
        $tasksDueThisWeek = Task::where('marketer_id', $marketerId)
            ->whereBetween('deadline', [now(), now()->addDays(7)])
            ->where('status', '!=', 'completed')
            ->count();

        // Get tasks completed in last 7 days
        $recentlyCompleted = Task::where('marketer_id', $marketerId)
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        // Calculate productivity score (weighted average)
        $productivityScore = 0;
        if ($totalTasks > 0) {
            $weightedScore = Task::where('marketer_id', $marketerId)
                ->select(DB::raw('SUM(progress * CASE priority 
                                WHEN "high" THEN 1.5 
                                WHEN "medium" THEN 1.0 
                                WHEN "low" THEN 0.5 
                                ELSE 0 END) as weighted_progress'))
                ->first();

            $maxPossibleScore = Task::where('marketer_id', $marketerId)
                ->select(DB::raw('SUM(100 * CASE priority 
                                   WHEN "high" THEN 1.5 
                                   WHEN "medium" THEN 1.0 
                                   WHEN "low" THEN 0.5 
                                   ELSE 0 END) as max_score'))
                ->first();

            if ($maxPossibleScore->max_score > 0) {
                $productivityScore = round(($weightedScore->weighted_progress / $maxPossibleScore->max_score) * 100, 1);
            }
        }

        return [
            'total' => $totalTasks,
            'pending' => Task::where('marketer_id', $marketerId)->where('status', 'pending')->count(),
            'in_progress' => Task::where('marketer_id', $marketerId)->where('status', 'in_progress')->count(),
            'completed' => $completedTasks,
            'overdue' => $overdueTasks,
            'completion_rate' => $completionRate,
            'average_progress' => round($averageProgress ?? 0, 1),
            'tasks_due_this_week' => $tasksDueThisWeek,
            'recently_completed' => $recentlyCompleted,
            'productivity_score' => $productivityScore,
            'high_priority' => Task::where('marketer_id', $marketerId)->where('priority', 'high')->count(),
            'medium_priority' => Task::where('marketer_id', $marketerId)->where('priority', 'medium')->count(),
            'low_priority' => Task::where('marketer_id', $marketerId)->where('priority', 'low')->count(),
        ];
    }
}

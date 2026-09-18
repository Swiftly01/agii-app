<?php

namespace App\Http\Controllers;

use App\Models\HrQuery;
use App\Models\HrQueryResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrQueryController extends Controller
{
    /**
     * Display all queries for HR/Admin staff
     */
    // public function index()
    // {
    //     // Check if user is HR/Admin
    //     if (!Auth::user()->is_admin) {
    //         abort(403, 'Unauthorized access');
    //     }
        
    //     $queries = HrQuery::with(['staff', 'responses'])
    //         ->latest()
    //         ->paginate(15);

    //     return view('hr.queries.index', compact('queries'));
    // }
    
    // In HrQueryController.php for index method
    public function index()
    {
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }
        
        $queries = HrQuery::with(['staff', 'responses'])
            ->latest()
            ->paginate(15);
        
        // Get staff members for the dropdown
        $staffMembers = \App\Models\StaffProfile::all(); // Adjust based on your model
        
        return view('hr.queries.index', compact('queries', 'staffMembers'));
    }
    
    // In HrQueryController.php for showForHr method
    public function showForHr($id)
    {
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }
        
        $query = HrQuery::with(['staff', 'responses.user', 'hr'])
            ->findOrFail($id);
        
        return view('hr.queries.show', compact('query'));
    }

    /**
     * Display staff's own queries
     */
    public function myQueries()
    {
        // Check if user has staff profile
        // if (!Auth::user()->staffProfile) {
        //     abort(403, 'No staff profile found');
        // }
        
        $staffProfileId = Auth::user()->staffProfile->id;
        
        $queries = HrQuery::where('staff_profile_id', $staffProfileId)
            ->with('responses')
            ->latest()
            ->paginate(10);

        return view('staff.hr-queries.index', compact('queries'));
    }

    /**
     * Store a query initiated by staff
     */
    public function storeByStaff(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);

        // Check if user has staff profile
        // if (!Auth::user()->staffProfile) {
        //     abort(403, 'No staff profile found');
        // }

        HrQuery::create([
            'staff_profile_id' => Auth::user()->staffProfile->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'initiated_by' => 'staff',
            'status' => 'open'
        ]);

        return back()->with('success', 'Query sent to HR successfully.');
    }

    /**
     * Store a query initiated by HR/Admin
     */
    public function storeByHr(Request $request)
    {
        // Check if user is HR/Admin
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }
        
        $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'subject' => 'required|max:255',
            'message' => 'required',
            'deadline' => 'nullable|date'
        ]);

        HrQuery::create([
            'staff_profile_id' => $request->staff_profile_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'initiated_by' => 'hr',
            'assigned_hr_id' => Auth::id(),
            'deadline' => $request->deadline,
            'status' => 'open'
        ]);

        return back()->with('success', 'Query sent to staff.');
    }

    /**
     * HR/Admin responds to a query
     */
    public function respondAsHr(Request $request, $id)
    {
        // Check if user is HR/Admin
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }
        
        $request->validate(['response' => 'required']);

        HrQueryResponse::create([
            'hr_query_id' => $id,
            'user_id' => Auth::id(),
            'response' => $request->response
        ]);

        HrQuery::where('id', $id)->update(['status' => 'answered']);

        return back()->with('success', 'Response sent.');
    }

    /**
     * Staff responds to a query
     */
    public function respondAsStaff(Request $request, $id)
    {
        $request->validate(['response' => 'required']);

        $query = HrQuery::findOrFail($id);

        // Check if staff owns this query
        if (!Auth::user()->staffProfile || 
            $query->staff_profile_id !== Auth::user()->staffProfile->id) {
            abort(403);
        }

        HrQueryResponse::create([
            'hr_query_id' => $query->id,
            'user_id' => Auth::id(),
            'response' => $request->response
        ]);

        $query->update(['status' => 'answered']);

        return back()->with('success', 'Response submitted.');
    }

    /**
     * Close a query (HR/Admin only)
     */
    public function close($id)
    {
        // Check if user is HR/Admin
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access');
        // }
        
        HrQuery::where('id', $id)->update(['status' => 'closed']);
        return back()->with('success', 'Query closed.');
    }

    /**
     * Show a single query for HR/Admin
     */
    // public function showForHr($id)
    // {
    //     // Check if user is HR/Admin
    //     if (!Auth::user()->is_admin) {
    //         abort(403, 'Unauthorized access');
    //     }
        
    //     $query = HrQuery::with(['staff', 'responses.user', 'hr'])
    //         ->findOrFail($id);
        
    //     return view('hr.queries.show', compact('query'));
    // }

    /**
     * Show a single query for staff
     */
    public function showForStaff($id)
    {
        $query = HrQuery::with(['staff', 'responses.user', 'hr'])
            ->findOrFail($id);

        // Check if staff owns this query
        if (!Auth::user()->staffProfile || 
            $query->staff_profile_id !== Auth::user()->staffProfile->id) {
            abort(403);
        }
        
        return view('staff.hr-queries.show', compact('query'));
    }
}
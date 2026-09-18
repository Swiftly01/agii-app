<?php
// app/Http/Controllers/StaffPortalController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $staffProfile = $user->staffProfile;
        
        return view('staff-portal.dashboard', compact('user', 'staffProfile'));
    }

    public function profile()
    {
        $user = Auth::user();
        $staffProfile = $user->staffProfile;
        
        return view('staff-portal.profile', compact('user', 'staffProfile'));
    }

    public function documents()
    {
        // Show staff documents
        return view('staff-portal.documents');
    }
}
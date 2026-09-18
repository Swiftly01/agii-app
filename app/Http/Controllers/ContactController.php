<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return view('others.contact'); // Make sure this matches your blade file name
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Send email to your team
            Mail::to('support@agii.ng')->send(new ContactFormMail($request->all()));
            
            // Optional: Send confirmation email to user
            Mail::to($request->email)->send(new ContactFormMail($request->all(), true));

            return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Sorry, something went wrong. Please try again later.')
                ->withInput();
        }
    }
}
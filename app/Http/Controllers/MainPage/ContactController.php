<?php

namespace App\Http\Controllers\MainPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Mail\ContactReceivedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){
        return view('mainpage.contactUs');
    }

    public function store(Request $request){
        // Handle contact form submission
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company_name' => 'nullable|string|max:150',
            'email' => 'required|email:rfc,dns|max:105',
            'phone' => [
                'nullable',
                'regex:/^\+?[0-9\s\-\(\)]{7,20}$/'
            ],
            'message' => 'required|string|min:10|max:500',
        ]);

        $exists = ContactUs::where('email', $validated['email'])
            ->where('message', $validated['message'])
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'message' => 'You have already submitted this message recently.'
            ]);
        }

        $validated['first_name'] = strip_tags($validated['first_name']);
        $validated['last_name'] = strip_tags($validated['last_name']);
        $validated['company_name'] = strip_tags($validated['company_name'] ?? '');
        $validated['message'] = strip_tags($validated['message']);
        $validated['status'] = 'pending';
        
        try {
            DB::transaction(function () use ($validated) {
                $contact = ContactUs::create($validated);

                Mail::to($contact->email)->send(new ContactReceivedMail($contact));
            });

            return back()->with('success', 'Your message has been sent successfully!');

        } catch (\Exception $e) {

            Log::error('Contact form error', [
                'message' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);

            return back()->withErrors(['error' => 'Something went wrong. Please try again later.']);
        }
    }
}

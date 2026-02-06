<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationRecord;
use App\Models\donations;
use Illuminate\Http\Request;

class AllDonationController extends Controller
{
    public function index()
    {
        $donations = donations::latest()->paginate(10);
        return view('admin.donations', compact('donations'));
    }

public function show($id)
{
    // Get the donation with its records
    $donation = donations::with('records')->findOrFail($id);
    // If you want pagination for the records
    $records = $donation->records()->paginate(10);

    return view('admin.donationrecord', compact('donation', 'records'));
}






}

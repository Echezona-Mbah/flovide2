<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;

class ReferralAlertController extends Controller
{
    public function resolve($id)
    {
        AdminNotification::findOrFail($id)->markResolved();
        return back()->with('success', 'Referral alert marked as resolved.');
    }
}
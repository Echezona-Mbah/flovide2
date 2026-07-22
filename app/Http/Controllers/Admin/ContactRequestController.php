<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Mail\ContactReceivedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactRequestController extends Controller
{
    public function index()
    {
        $contactRequests = ContactUs::orderBy('created_at', 'desc')->get();
        return view('admin.contact-requests', compact('contactRequests'));
    }

    public function updateStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        $contact = ContactUs::findOrFail($id);

        $contact->status = $request->status;
        $contact->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }

    public function reply($id) {
        //
    }

    public function destroy($id)
    {
        try {

            $contactRequest = ContactUs::find($id);

            if (!$contactRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Contact request not found.'
                ], 404);
            }

            $contactRequest->delete();

            return response()->json([
                'status' => true,
                'message' => 'Contact request moved to the trash successfully.'
            ]);

        } catch (\Exception $e) {

            Log::error('Delete contact request failed.', [
                'contact_request_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to delete the contact request.'
            ], 500);
        }
    }
}

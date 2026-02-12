<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;

class IbanqBeneficiaryApprovalController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }

    /**
     * Approve a beneficiary
     * Requires 2FA code in header "User-2-Factor-Code"
     */
    public function approveBeneficiary(Request $request, $beneficiaryId)
    {
        $request->validate([
            'twoFactorCode' => 'required|string|size:6', // 6-character code from 2FA app
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post("/beneficiaries/{$beneficiaryId}/approve", [
                'headers' => [
                    'User-2-Factor-Code' => $request->input('twoFactorCode'),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary approved successfully',
                'data' => $response->json()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a beneficiary
     */
    public function rejectBeneficiary(Request $request, $beneficiaryId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post("/beneficiaries/{$beneficiaryId}/reject", [
                'json' => [
                    'reason' => $request->input('reason')
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary rejected successfully',
                'data' => $response->json()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

     /**
     * View approval details for a beneficiary
     */
    public function viewApprovalDetails($beneficiaryId)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->get("/beneficiaries/{$beneficiaryId}/approval");

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

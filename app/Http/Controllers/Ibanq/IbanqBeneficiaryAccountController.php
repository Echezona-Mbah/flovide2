<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;

class IbanqBeneficiaryAccountController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }

    /**
     * Add an account to a beneficiary
     */
    public function addAccount(Request $request, $beneficiaryId)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'accountHolder' => 'required|string|max:70',
            'nickname' => 'required|string|max:70',
            'accountNumber' => 'nullable|string|max:34',
            'iban' => 'nullable|string|max:30',
            'swiftBic' => 'nullable|string|max:50',
            'sortCode' => 'nullable|string|max:28',
            'bankGiro' => 'nullable|string|max:34',
            'aba' => 'nullable|string|max:28',
            'ach' => 'nullable|string|max:28',
            'bsb' => 'nullable|string|max:28',
            'institutionNumber' => 'nullable|string|max:28',
            'bankCode' => 'nullable|string|max:28',
            'branchCode' => 'nullable|string|max:28',
            'ifsc' => 'nullable|string|max:28',
            'routingInstructions' => 'nullable|string',
            'defaultReference' => 'required|string|max:170',
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post("/beneficiaries/{$beneficiaryId}/accounts", [
                'json' => $request->only([
                    'currency', 'accountHolder', 'nickname', 'accountNumber', 'iban',
                    'swiftBic', 'sortCode', 'bankGiro', 'aba', 'ach', 'bsb',
                    'institutionNumber', 'bankCode', 'branchCode', 'ifsc',
                    'routingInstructions', 'defaultReference'
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account added to beneficiary successfully',
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
     * List accounts for a beneficiary
     */
    public function listAccounts(Request $request, $beneficiaryId)
    {
        // Validate query parameters
        $request->validate([
            'offset' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:1000',
            'sort' => 'nullable|array',
            'sort.currency' => 'nullable|in:asc,desc',
            'sort.accountHolder' => 'nullable|in:asc,desc',
            'sort.nickname' => 'nullable|in:asc,desc',
            'sort.accountNumber' => 'nullable|in:asc,desc',
            'sort.iban' => 'nullable|in:asc,desc',
            'sort.createdAt' => 'nullable|in:asc,desc',
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $query = [
                'offset' => $request->input('offset', 0),
                'limit' => $request->input('limit', 100),
            ];

            // Add sort parameters if provided
            if ($request->has('sort')) {
                foreach ($request->input('sort') as $key => $direction) {
                    $query["sort[$key]"] = $direction;
                }
            }

            $response = $client->get("/beneficiaries/{$beneficiaryId}/accounts", [
                'query' => $query
            ]);

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



    /**
     * View details of a specific account for a beneficiary
     */
    public function viewAccount($beneficiaryId, $beneficiaryAccountId)
    {
        try {
            // Get authenticated HTTP client
            $client = $this->ibanq->authenticatedClient();

            // Send GET request to IBANQ API
            $response = $client->get("/beneficiaries/{$beneficiaryId}/accounts/{$beneficiaryAccountId}");

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



    /**
     * Delete a specific account for a beneficiary
     */
    public function deleteAccount($beneficiaryId, $beneficiaryAccountId)
    {
        try {
            // Get authenticated HTTP client
            $client = $this->ibanq->authenticatedClient();

            // Send DELETE request to IBANQ API
            $response = $client->delete("/beneficiaries/{$beneficiaryId}/accounts/{$beneficiaryAccountId}");

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully',
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
     * Update a specific account for a beneficiary
     */
    public function updateAccount(Request $request, $beneficiaryId, $beneficiaryAccountId)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'currency' => 'nullable|string|size:3',
            'accountHolder' => 'nullable|string|max:70',
            'nickname' => 'nullable|string|max:70',
            'accountNumber' => 'nullable|string|max:34',
            'iban' => 'nullable|string|max:30',
            'swiftBic' => 'nullable|string|max:11',
            'sortCode' => 'nullable|string|max:28',
            'bankGiro' => 'nullable|string|max:34',
            'aba' => 'nullable|string|max:28',
            'ach' => 'nullable|string|max:28',
            'bsb' => 'nullable|string|max:28',
            'institutionNumber' => 'nullable|string|max:28',
            'bankCode' => 'nullable|string|max:28',
            'branchCode' => 'nullable|string|max:28',
            'ifsc' => 'nullable|string|max:28',
            'routingInstructions' => 'nullable|string',
            'defaultReference' => 'nullable|string|max:170',
        ]);

        try {
            // Get authenticated HTTP client
            $client = $this->ibanq->authenticatedClient();

            // Send PATCH request to update account
            $response = $client->patch(
                "/v2/beneficiaries/{$beneficiaryId}/accounts/{$beneficiaryAccountId}",
                [
                    'json' => $validated
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Account updated successfully',
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

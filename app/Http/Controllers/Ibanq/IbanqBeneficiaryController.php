<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;


class IbanqBeneficiaryController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }

    /**
     * List IBANQ beneficiaries
     */
    public function listBeneficiaries(Request $request)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // Query parameters with defaults
            $query = [
                'uniqueReference' => $request->input('uniqueReference'),
                'country' => $request->input('country'),
                'type' => $request->input('type'), // individual / corporate
                'name' => $request->input('name'),
                'workflowStatus' => $request->input('workflowStatus'), // new, pending_approval, rejected, approved
                'canApprove' => $request->input('canApprove'),
                'offset' => $request->input('offset', 0),
                'limit' => $request->input('limit', 100),
                'sort[createdAt]' => $request->input('sort.createdAt', 'desc'),
            ];

            // Remove null values
            $query = array_filter($query, fn($value) => !is_null($value));

            // GET request to /beneficiaries
            $response = $client->get('/beneficiaries', $query);

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
     * Create a new beneficiary
     */
    public function createBeneficiary(Request $request)
    {
        // Validate request
        $request->validate([
            'type' => 'required|in:individual,corporate',
            'uniqueReference' => 'required|string|max:16',
            'customerReference' => 'nullable|string|max:35',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:30',

            // individual fields
            'firstNames' => 'required_if:type,individual|string|max:140',
            'lastName' => 'required_if:type,individual|string|max:140',

            // corporate field
            'name' => 'required_if:type,corporate|string|max:200',

            // required for both
            'address' => 'required|array',
        ]);

        try {
            // Call IBANQ API
            $client = $this->ibanq->authenticatedClient();

            $payload = [
                'type' => $request->type,
                'uniqueReference' => $request->uniqueReference,
                'customerReference' => $request->customerReference,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            if ($request->type === 'individual') {
                $payload['firstNames'] = $request->firstNames;
                $payload['lastName'] = $request->lastName;
            }

            if ($request->type === 'corporate') {
                $payload['name'] = $request->name;
            }

            $response = $client->post('/v2/beneficiaries', $payload);
            $responseData = $response->json();

            // Store in database if successful
            // if ($response->successful()) {
            //     $userId = auth('api')->id() ?? auth()->id();
            //     $beneficia = Beneficia::create([
            //         'recipient_id'      => $responseData['id'] ?? null,
            //         'country'           => $request->country,
            //         'alias'             => $responseData['alias'] ?? $request->uniqueReference,
            //         'type'              => $request->type,
            //         'account_name'      => $request->bankDetails['accountName'] ?? null,
            //         'account_number'    => $request->bankDetails['accountNumber'] ?? null,
            //         'bank'              => $responseData['bank_account']['bank_name'] ?? null,
            //         'currency'          => $request->currency,
            //         'user_id'           => $userId,
            //         'default_reference' => 'Invoice',
            //     ]);
            // }

            // Return for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => $response->successful(),
                    'data' => $responseData,
                ], $response->status());
            }

            // Return for web requests
            return redirect()->back()
                ->with('success', 'Beneficiary created successfully!');

        } catch (\Exception $e) {
            // API request error
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }

            // Web request error
            return redirect()->back()
                ->with('error', 'Failed to create beneficiary: ' . $e->getMessage());
        }
    }






    /**
     * Update a beneficiary's core details (without affecting accounts)
     */
    public function updateBeneficiary(Request $request, $beneficiaryId)
    {
        // Validate required fields
        $request->validate([
            'type' => 'required|in:individual,corporate',
            'uniqueReference' => 'required|string|max:16',
            'customerReference' => 'nullable|string|max:35',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:30',
            'firstNames' => 'required_if:type,individual|string|max:140',
            'lastName' => 'required_if:type,individual|string|max:140',
            'address' => 'required_if:type,individual|array',
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            // Build payload
            $payload = [
                'type' => $request->input('type'),
                'uniqueReference' => $request->input('uniqueReference'),
                'customerReference' => $request->input('customerReference'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ];

            // Include individual-specific fields
            if ($request->input('type') === 'individual') {
                $payload['firstNames'] = $request->input('firstNames');
                $payload['lastName'] = $request->input('lastName');
                $payload['address'] = $request->input('address'); // array with line1, line2, city, postalCode, country
            }

            // PATCH request to update the beneficiary
            $response = $client->patch("/v2/beneficiaries/{$beneficiaryId}", [
                'json' => $payload
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary updated successfully',
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
     * View details of a beneficiary (personal or company details)
     */
    public function viewBeneficiary($beneficiaryId)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // GET request to fetch beneficiary details
            $response = $client->get("/beneficiaries/{$beneficiaryId}");

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
     * Delete a beneficiary and all associated accounts
     */
    public function deleteBeneficiary($beneficiaryId)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // DELETE request to remove the beneficiary
            $response = $client->delete("/beneficiaries/{$beneficiaryId}");

            // Check if deletion was successful (HTTP 200 or 204)
            if (in_array($response->getStatusCode(), [200, 204])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Beneficiary and associated accounts deleted successfully'
                ]);
            }

            // If API returns an error
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete beneficiary',
                'data' => $response->json()
            ], $response->getStatusCode());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


}

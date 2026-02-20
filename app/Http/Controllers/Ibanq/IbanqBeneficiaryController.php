<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use App\Models\Beneficia;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;
use Pest\Support\Str;

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




public function createBeneficiary(Request $request, $userId = null, $personalId = null)

{



    if ($request->typeofuser === 'business') {
        $userId = auth('web')->id() ?? auth('api')->id();
    }

    if ($request->typeofuser === 'personal') {
        $personalId = auth('personal')->id() ?? auth('personal-api')->id();
    }

    // dd($personalId);

    // Validate request with custom messages
    $validator = \Validator::make($request->all(), [
        'type' => 'required|in:individual,corporate',
        'email' => 'nullable|email|max:200',
        'phone' => 'nullable|string|max:30',
        'firstNames' => 'nullable|required_if:type,individual|string|max:100',
        'lastName' => 'nullable|required_if:type,individual|string|max:100',
        'name' => 'nullable|required_if:type,corporate|string|max:200',
        'address.addressLine1' => 'required|string|max:150',
        'address.addressLine2' => 'nullable|string|max:150',
        'address.buildingName' => 'nullable|string|max:100',
        'address.city' => 'required|string|max:100',
        'address.state' => 'nullable|string|max:50',
        'address.postcode' => 'nullable|string|max:20',
        'address.country' => 'required|string|size:2',
        'bank.currency' => 'required|string|size:3',
        'bank.accountHolder' => 'required|string|max:100',
        'bank.nickname' => 'required|string|max:100',
        'bank.accountNumber' => 'nullable|string|max:34',
        'bank.bankCode' => 'required_if:bank.currency,NGN|nullable|string|max:10',
        'bank.iban' => 'required_unless:bank.currency,NGN|nullable|string|max:34',
        'bank.swiftBic' => 'nullable|string|max:50',
        'bank.defaultReference' => 'nullable|string|max:100',
    ], [
        'type.required' => 'Please select whether the beneficiary is an Individual or a Corporate entity.',
        'type.in' => 'The beneficiary type must be either Individual or Corporate.',
        'firstNames.required_if' => 'First Name is required for an Individual beneficiary.',
        'lastName.required_if' => 'Last Name is required for an Individual beneficiary.',
        'name.required_if' => 'Company Name is required for a Corporate beneficiary.',
        'address.addressLine1.required' => 'Address Line 1 is required for the beneficiary.',
        'address.city.required' => 'City is required for the beneficiary address.',
        'address.country.required' => 'Country code is required for the beneficiary.',
        'bank.currency.required' => 'Currency is required for the beneficiary bank account.',
        'bank.accountHolder.required' => 'Account Holder name is required for the bank account.',
        'bank.accountNumber.required' => 'Account Number is required for the bank account.',
    ]);

    // If validation fails
    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        } else {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    $bank = $request->input('bank', []);
    $addressInput = $request->input('address', []);

    // Generate unique references
    $uniqueReference = strtoupper(\Str::random(7));
    $customerReference = strtoupper(\Str::random(7));

    try {
        $client = $this->ibanq->authenticatedClient();

        // Beneficiary payload
        $payload = array_filter([
            'type' => $request->type,
            'uniqueReference' => $uniqueReference,
            'customerReference' => $customerReference,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => array_filter([
                'addressLine1' => $addressInput['addressLine1'] ?? null,
                'addressLine2' => $addressInput['addressLine2'] ?? null,
                'buildingName' => $addressInput['buildingName'] ?? null,
                'city' => $addressInput['city'] ?? null,
                'state' => $addressInput['state'] ?? null,
                'postcode' => $addressInput['postcode'] ?? null,
                'country' => $addressInput['country'] ?? null,
            ]),
        ]);

        if ($request->type === 'individual') {
            $payload['firstNames'] = $request->firstNames;
            $payload['lastName'] = $request->lastName;
        } else {
            $payload['name'] = $request->name;
        }

        // Create beneficiary on IFX
        $beneficiaryResponse = $client->post('/v2/beneficiaries', $payload);
        $beneficiaryData = $beneficiaryResponse->json();

        if (!isset($beneficiaryData['id'])) {
            $message = $beneficiaryData['message'] ?? 'Beneficiary creation failed';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $message], 400);
            } else {
                return redirect()->back()->with('error', $message);
            }
        }

        $beneficiaryId = $beneficiaryData['id'];

        // Create account on IFX
        $accountPayload = array_filter([
            'currency' => $bank['currency'] ?? null,
            'accountHolder' => $bank['accountHolder'] ?? null,
            'nickname' => $bank['nickname'] ?? null,
            'accountNumber' => $bank['accountNumber'] ?? null,
            'bankCode' => $bank['bankCode'] ?? null,
            'iban' => $bank['iban'] ?? null,
            'swiftBic' => $bank['swiftBic'] ?? null,
            'defaultReference' => $bank['defaultReference'] ?? $bank['nickname'] ?? 'default_ref',
        ]);

        $accountResponse = $client->post("/v2/beneficiaries/{$beneficiaryId}/accounts", $accountPayload);
        $accountData = $accountResponse->json();

        if (isset($accountData['messages'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'beneficiary' => $beneficiaryData,
                    'account_error' => $accountData['messages']
                ], 400);
            } else {
                return redirect()->back()->with('error', implode(', ', $accountData['messages']));
            }
        }

        // Store locally
        $beneficia = \App\Models\Beneficia::create([
            'bank' => $bank['nickname'] ?? null,
            'country_id' => $request->input('country_id') ?? null,
            'account_number' => $bank['accountNumber'] ?? null,
            'account_name' => $bank['accountHolder'] ?? null,
            'beneficiary_name' => $request->input('name') ?? null,
            'recipient_id' => $beneficiaryData['id'] ?? null,
            'account_id' => $accountData['id'] ?? null,
            'unique_reference' => $uniqueReference,
            'customer_reference' => $customerReference,
            'address_line1' => $addressInput['addressLine1'] ?? null,
            'address_line2' => $addressInput['addressLine2'] ?? null,
            'building_name' => $addressInput['buildingName'] ?? null,
            'city' => $addressInput['city'] ?? null,
            'state' => $addressInput['state'] ?? null,
            'postcode' => $addressInput['postcode'] ?? null,
            'country' => $addressInput['country'] ?? null,
            'alias' => $bank['nickname'] ?? null,
            'type' => $request->type,
            'currency' => $bank['currency'] ?? null,
            'default_reference' => $accountPayload['defaultReference'] ?? 'default_ref',
            'sort_code' => $bank['bankCode'] ?? null,
            'swift_bic' => $bank['swiftBic'] ?? null,
            'user_id' => $userId,
            'personal_id' => $personalId,
        ]);

        // Return success (API or Web)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'beneficiary' => $beneficiaryData,
                'account' => $accountData,
                'local' => $beneficia
            ], 201);
        } else {
            return redirect()->back()->with('success', 'Beneficiary created successfully.');
        }

    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        } else {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

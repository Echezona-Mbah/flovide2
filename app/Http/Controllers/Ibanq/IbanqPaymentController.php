<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;

class IbanqPaymentController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }


   public function createIbanqPayment(array $data)
{
    try {
        $client = $this->ibanq->authenticatedClient();

        $response = $client->withHeaders([
            'Accept' => 'application/json',
        ])->post('/v2/payments', $data);

        $body = $response->json(); // decode JSON

        // If the response contains an "id" but no error, treat as success
        if (isset($body['id'])) {
            return [
                'success' => true,
                'data' => $body,
                'error' => null,
            ];
        }

        // Otherwise return as error
        return [
            'success' => false,
            'data' => $body,
            'error' => $body['message'] ?? 'Unknown error from IBANQ',
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'data' => null,
            'error' => $e->getMessage(),
        ];
    }
}



    /**
     * Create a new payment
     */

// public function createPayment(Request $request)
// {
//     $validated = $request->validate([
//         'beneficiaryAccountId' => 'required|uuid',
//         'amount' => 'required|numeric|min:0.01',
//         'currency' => 'required|string|size:3',
//         'reference' => 'required|string|max:140',
//         'sendOn' => 'nullable|date|date_format:Y-m-d',
//         'arriveBy' => 'nullable|date|date_format:Y-m-d',
//         'paymentMethod' => 'nullable|string|max:50',
//         'purposeOfPayment' => 'nullable|array',
//         'purposeOfPayment.purposeCode' => 'nullable|string|max:10',
//         'purposeOfPayment.invoiceNumber' => 'nullable|string|max:50',
//         'purposeOfPayment.invoiceDate' => 'nullable|date|date_format:Y-m-d',
//         'purposeOfPayment.charityNumber' => 'nullable|numeric',
//     ]);

//     // Ensure only one of sendOn or arriveBy is set
//     if (!empty($validated['sendOn']) && !empty($validated['arriveBy'])) {
//         return response()->json([
//             'success' => false,
//             'error' => 'You cannot specify both sendOn and arriveBy dates.'
//         ], 422);
//     }

//     // Build payload
//     $payload = [
//         'beneficiaryAccountId' => $validated['beneficiaryAccountId'],
//         'amount' => $validated['amount'],
//         'currency' => $validated['currency'],
//         'reference' => $validated['reference'],
//     ];

//     // Only add if provided
//     if (!empty($validated['sendOn'])) {
//         $payload['sendOn'] = $validated['sendOn'];
//     }
//     if (!empty($validated['arriveBy'])) {
//         $payload['arriveBy'] = $validated['arriveBy'];
//     }
//     if (!empty($validated['paymentMethod'])) {
//         $payload['paymentMethod'] = $validated['paymentMethod'];
//     }

//     // Purpose of payment
//     if (!empty($validated['purposeOfPayment'])) {
//         $pp = $validated['purposeOfPayment'];
//         $payload['purposeOfPayment'] = array_filter([
//             'purposeCode' => $pp['purposeCode'] ?? null,
//             'invoiceNumber' => $pp['invoiceNumber'] ?? null,
//             'invoiceDate' => $pp['invoiceDate'] ?? null,
//             'charityNumber' => $pp['charityNumber'] ?? null,
//         ], fn($v) => $v !== null); // remove null fields
//     }

//     try {
//         $client = $this->ibanq->authenticatedClient();

//         // Post JSON payload
//         $response = $client->withHeaders([
//             'Accept' => 'application/json',
//         ])->post('/v2/payments', $payload);

//         return response()->json([
//             'success' => true,
//             'message' => 'Payment created successfully',
//             'data' => $response->json()
//         ], $response->status());

//     } catch (\Exception $e) {
//         return response()->json([
//             'success' => false,
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }










     /**
     * List payments with optional filters
     */
    public function listPayments(Request $request)
    {
        // Define allowed filters and query params
        $filters = $request->only([
            'beneficiary',
            'bankAccount',
            'currency',
            'amount',
            'amountFrom',
            'amountTo',
            'country',
            'sendOn',
            'sendOnFrom',
            'sendOnTo',
            'arriveBy',
            'arriveByFrom',
            'arriveByTo',
            'status',
            'reference',
            'batchReference',
            'offset',
            'limit',
        ]);

        // Sort options example: sort[field] = asc|desc
        $sort = $request->input('sort', []); // e.g., ['sendOn' => 'desc']
        foreach ($sort as $field => $direction) {
            $filters["sort[$field]"] = $direction;
        }

        try {
            // Get authenticated HTTP client
            $client = $this->ibanq->authenticatedClient();

            // Send GET request with filters
            $response = $client->get('/payments', [
                'query' => $filters
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
     * Create a third-party payment
     */
    public function createThirdPartyPayment(Request $request)
    {
        $request->validate([
            'beneficiaryAccountId' => 'required|uuid',
            'amount'               => 'required|numeric',
            'currency'             => 'required|string|size:3',
            'reference'            => 'required|string|max:140',
            'ultimateDebtorName'   => 'required|string|max:140',
            'ultimateDebtorStreetName' => 'required|string|max:33',
            'ultimateDebtorCity'   => 'required|string|max:25',
            'ultimateDebtorCountry'=> 'required|string|size:2',
            'ultimateDebtorPostCode'=> 'required|string|max:10',
            'ultimateDebtorIdentificationNumber' => 'required|string|max:34',
        ]);

        $payload = $request->only([
            'beneficiaryAccountId',
            'batchId',
            'amount',
            'currency',
            'reference',
            'sendOn',
            'arriveBy',
            'paymentMethod',
            'ultimateDebtorName',
            'ultimateDebtorBuildingNumber',
            'ultimateDebtorBuildingName',
            'ultimateDebtorStreetName',
            'ultimateDebtorCity',
            'ultimateDebtorCountry',
            'ultimateDebtorPostCode',
            'ultimateDebtorIdentificationNumber',
            'purposeOfPayment',
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post('/v2/third-party-payments', [
                'json' => $payload
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
     * View details of a payment
     */
    public function viewPaymentDetails($paymentId)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->get("/payments/{$paymentId}");

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
     * Approve a payment created by another user
     *
     * @param string $paymentId
     * @param Request $request
     */
public function approvePayment(Request $request, $paymentId)
{
    $request->validate([
        'twoFactorCode' => 'required|string|size:6', // SCA 2FA code
    ]);

    try {
        // Get authenticated client
        $client = $this->ibanq->authenticatedClient()
            ->withHeaders([
                'User-2-Factor-Code' => $request->twoFactorCode
            ]);

        // Send POST request to approve payment
        $response = $client->post("/v2/payments/{$paymentId}/approve", []);

        return response()->json([
            'success' => $response->successful(),
            'data' => $response->json(),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}




     /**
     * Reject a payment created by another user
     *
     * @param string $paymentId
     * @param Request $request
     */
    public function rejectPayment(Request $request, $paymentId)
    {
        $request->validate([
            'reason' => 'required|string|max:255', // Reason for rejection
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post("/payments/{$paymentId}/reject", [
                'json' => [
                    'reason' => $request->reason
                ]
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




}
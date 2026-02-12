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

    /**
     * Create a new payment
     */
    public function createPayment(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'beneficiaryAccountId' => 'required|uuid',
            'batchId' => 'nullable|uuid',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'reference' => 'required|string|max:140',
            'sendOn' => 'nullable|date|date_format:Y-m-d',
            'arriveBy' => 'nullable|date|date_format:Y-m-d',
            'paymentMethod' => 'nullable|string|max:50',
            'purposeOfPayment' => 'nullable|array',
            'purposeOfPayment.code' => 'nullable|string|max:10',
            'purposeOfPayment.text' => 'nullable|string|max:140',
        ]);

        // Ensure both sendOn and arriveBy are not set together
        if (!empty($validated['sendOn']) && !empty($validated['arriveBy'])) {
            return response()->json([
                'success' => false,
                'error' => 'You cannot specify both sendOn and arriveBy dates.'
            ], 422);
        }

        try {
            // Get authenticated HTTP client
            $client = $this->ibanq->authenticatedClient();

            // Send POST request to create payment
            $response = $client->post('/v2/payments', [
                'json' => $validated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment created successfully',
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
            'twoFactorCode' => 'required|string|size:6', // The SCA 2FA code
        ]);

        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->post("/payments/{$paymentId}/approve", [
                'headers' => [
                    'User-2-Factor-Code' => $request->twoFactorCode
                ],
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
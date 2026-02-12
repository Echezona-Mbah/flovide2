<?php

namespace App\Http\Controllers;

use App\Services\IbanqAuthService;

class IbanqTestController extends Controller
{
    public function test(IbanqAuthService $ibanq)
    {
        try {
            $client = $ibanq->authenticatedClient();

            // Example: list beneficiaries (sandbox)
            $response = $client->get('/beneficiaries');

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

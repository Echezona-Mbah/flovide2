<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;

class IbanqReferenceController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }

    /**
     * Get required fields for bank accounts per country and currency
     *
     * @param Request $request
     * @param string $country 2-letter ISO country code
     * @param string $currency 3-letter ISO currency code
     */
    public function getBankAccountRequirements(Request $request, $country, $currency)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            $response = $client->get("/reference/rules/bankAccount/{$country}/{$currency}");

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
     * Get required fields for a beneficiary based on type, country, and currency
     *
     * @param Request $request
     * @param string $type Individual or Corporate
     * @param string $country 2-letter ISO country code
     * @param string $currency 3-letter ISO currency code
     */
    public function getBeneficiaryRequirements(Request $request, $type, $country, $currency)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // API endpoint
            $endpoint = "/reference/rules/beneficiary/{$type}/{$country}/{$currency}";

            $response = $client->get($endpoint);

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


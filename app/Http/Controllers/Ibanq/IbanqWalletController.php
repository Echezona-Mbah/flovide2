<?php

namespace App\Http\Controllers\Ibanq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IbanqAuthService;


class IbanqWalletController extends Controller
{
    protected $ibanq;

    public function __construct(IbanqAuthService $ibanq)
    {
        $this->ibanq = $ibanq;
    }

    /**
     * View a list of IBANQ wallets
     */
    public function listWallets(Request $request)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // Query parameters with defaults
            $params = [
                'offset' => $request->input('offset', 0),
                'limit'  => $request->input('limit', 100),
                'sort'   => [
                    'accountName'   => $request->input('sort.accountName', 'asc'),
                    'iban'          => $request->input('sort.iban', 'asc'),
                    'swiftBic'      => $request->input('sort.swiftBic', 'asc'),
                    'sortCode'      => $request->input('sort.sortCode', 'asc'),
                    'accountNumber' => $request->input('sort.accountNumber', 'asc'),
                ]
            ];

            // Flatten sort for query string
            $query = [
                'offset' => $params['offset'],
                'limit' => $params['limit'],
                'sort[accountName]' => $params['sort']['accountName'],
                'sort[iban]' => $params['sort']['iban'],
                'sort[swiftBic]' => $params['sort']['swiftBic'],
                'sort[sortCode]' => $params['sort']['sortCode'],
                'sort[accountNumber]' => $params['sort']['accountNumber'],
            ];

            // GET request to /wallets
            $response = $client->get('/wallets', $query);

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
     * View wallet details and balances
     */
    public function walletDetails($walletId)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // GET request to /wallets/{walletId}/balances
            $response = $client->get("/wallets/{$walletId}");

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
     * List transactions for a wallet and currency
     */
    public function walletTransactions(Request $request, $walletId, $currency)
    {
        try {
            $client = $this->ibanq->authenticatedClient();

            // Collect filters from request
            $query = [
                'amount' => $request->input('amount'),
                'amountFrom' => $request->input('amountFrom'),
                'amountTo' => $request->input('amountTo'),
                'type' => $request->input('type'), // debit/credit
                'date' => $request->input('date'),
                'dateFrom' => $request->input('dateFrom'),
                'dateTo' => $request->input('dateTo'),
                'otherParty' => $request->input('otherParty'),
                'reference' => $request->input('reference'),
                'accountNumber' => $request->input('accountNumber'),
                'swiftBic' => $request->input('swiftBic'),
                'sortCode' => $request->input('sortCode'),
                'reasonForReturn' => $request->input('reasonForReturn'),
                'offset' => $request->input('offset', 0),
                'limit' => $request->input('limit', 100),
                'sort[date]' => $request->input('sort.date', 'desc'),
            ];

            // Remove null values
            $query = array_filter($query, fn($value) => !is_null($value));

            $response = $client->get("/wallets/{$walletId}/transactions/{$currency}", $query);

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
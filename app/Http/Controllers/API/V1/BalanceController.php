<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/v1/balances",
     *     tags={"Balances"},
     *     summary="Fetch all balances",
     *     description="Returns a list of balances filtered by currency",
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         required=false,
     *         description="Filter by currency code",
     *         @OA\Schema(type="string", example="GBP")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of balances",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="currency", type="string"),
     *             @OA\Property(property="balance", type="integer"),
     *             @OA\Property(property="default_currency", type="string"),
     *             @OA\Property(property="default_currency_balance", type="integer"),
     *             @OA\Property(property="addresses", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="created", type="string", format="date-time")
     *         ))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = auth()->user(); // Get the logged-in user
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Optional: Add currency filter
        if ($request->has('currency') && strtoupper($request->currency) !== strtoupper($user->currency)) {
            return response()->json([]); 
        }
    
        $balance = [
            'id' => $user->id,
            'name' => $user->business_name ?? $user->name,
            'currency' => $user->currency,
            'balance' => (int) $user->balance,
            'default_currency' => $user->default_currency,
            'default_currency_balance' => (int) $user->default_currency_balance,
            'addresses' => $user->addresses ?? [
                'name' => $user->business_name,
                'email' => $user->email,

            ],
            'created' => $user->created_at->toIso8601String(),
        ];
    
        return response()->json([$balance]); 
    }
    
    /**
 * @OA\Post(
 *     path="/api/v1/balances",
 *     tags={"Balances"},
 *     summary="Create a new balance",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "currency"},
 *             @OA\Property(property="name", type="string", example="Main"),
 *             @OA\Property(property="currency", type="string", example="GBP")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Balance created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="string", format="uuid", example="88fe6e8a-bd3b-11e9-821e-4180c1a9232a"),
 *             @OA\Property(property="name", type="string", example="Main"),
 *             @OA\Property(property="currency", type="string", example="GBP"),
 *             @OA\Property(property="balance", type="integer", example=10000),
 *             @OA\Property(property="default_currency", type="string", example="GBP"),
 *             @OA\Property(property="default_currency_balance", type="integer", example=10000),
 *             @OA\Property(
 *                 property="addresses",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="details", type="object",
 *                         @OA\Property(property="account_name", type="string", example="John Doe"),
 *                         @OA\Property(property="account_number", type="string", example="12345678"),
 *                         @OA\Property(property="sort_code", type="string", example="000000")
 *                     ),
 *                     @OA\Property(property="type", type="string", example="local"),
 *                     @OA\Property(property="supported_channels", type="array", @OA\Items(type="string", example="FP")),
 *                     @OA\Property(property="active", type="boolean", example=true)
 *                 )
 *             ),
 *             @OA\Property(property="created", type="string", format="date-time", example="2025-05-28T10:00:00+00:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request"
 *     )
 * )
 */

 public function store(Request $request)
 {
     $validated = $request->validate([
         'name' => 'required|string',
         'currency' => 'required|string|size:3',
     ]);
 
     $user = new User();
     $user->id = Str::uuid(); // UUID for balance ID
     $user->business_name = $validated['name'];
     $user->currency = strtoupper($validated['currency']);
     $user->balance = 0;
     $user->default_currency = strtoupper($validated['currency']);
     $user->default_currency_balance = 0;
 
     // Example address format
     $user->addresses = [
         [
             'details' => [
                 'account_name' => $validated['name'],
                 'account_number' => '12345678',
                 'sort_code' => '000000'
             ],
             'type' => 'local',
             'supported_channels' => ['FP', 'BACS', 'CHAPS'],
             'active' => true
         ]
     ];
 
     $user->save();
 
     return response()->json([
         'id' => $user->id,
         'name' => $user->business_name,
         'currency' => $user->currency,
         'balance' => (int) $user->balance,
         'default_currency' => $user->default_currency,
         'default_currency_balance' => (int) $user->default_currency_balance,
         'addresses' => $user->addresses,
         'created' => $user->created_at->toIso8601String(),
     ]);
 }
        /**
         * @OA\Get(
         *     path="/api/v1/balances/{id}",
         *     tags={"Balances"},
         *     summary="Fetch single balance",
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Balance details",
         *         @OA\JsonContent(
         *             @OA\Property(property="id", type="integer"),
         *             @OA\Property(property="name", type="string"),
         *             @OA\Property(property="amount", type="number")
         *         )
         *     ),
         *     @OA\Response(response=404, description="Balance not found")
         * )
         */
        public function show($id)
        {
            return response()->json([
                'id' => $id,
                'name' => 'Wallet NGN',
                'amount' => 1000
            ]);
        }
    
        /**
         * @OA\Patch(
         *     path="/api/v1/balances/{id}",
         *     tags={"Balances"},
         *     summary="Update balance name",
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"name"},
         *             @OA\Property(property="name", type="string", example="Main Wallet")
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Balance updated successfully",
         *         @OA\JsonContent(
         *             @OA\Property(property="id", type="integer"),
         *             @OA\Property(property="name", type="string")
         *         )
         *     ),
         *     @OA\Response(response=404, description="Balance not found")
         * )
         */
        public function update(Request $request, $id)
        {
            return response()->json([
                'id' => $id,
                'name' => $request->name
            ]);
        }
    }
    
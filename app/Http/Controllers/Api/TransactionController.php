<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionRequest;
use App\Models\Transaction;
use App\Services\Api\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param void
     * @return void
     */
    public function __construct(
        protected TransactionService $transactionService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = $this->transactionService->index($request);

        if ($result['success']) {
            return response()
                ->json([
                    'status' => true,
                    'data' => $result['data'],
                    'message' => 'Transactions fetched successfully.',
                ]);
        } else {
            return response()
                ->json([
                    'status' => false,
                    'message' => $result['message'],
                ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $result = $this->transactionService->store($request->validated());

        if ($result['success']) {
            return response()
                ->json([
                    'status' => true,
                    'data' => $result['data'],
                    'message' => 'Transaction stored successfully.',
                ])
                ->header('Cache-Control', 'no-store');
        } else {
            return response()
                ->json([
                    'status' => false,
                    'message' => $result['message'],
                ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'status' => ['required', 'in:accepted,failed'],
            'transaction_id' => ['required', 'string', 'exists:transactions,transaction_id'],
        ]);

        $transaction = Transaction::where([
            'transaction_id' => $validatedData['transaction_id'],
        ])
            ->firstOrFail();

        $result = $this->transactionService->update($validatedData, $transaction);

        if ($result['success']) {
            return response()
                ->json([
                    'status' => true,
                    'data' => $result['data'],
                    'message' => 'Transaction updated successfully.',
                ]);
        } else {
            return response()
                ->json([
                    'status' => false,
                    'message' => $result['message'],
                ], 500);
        }
    }
}

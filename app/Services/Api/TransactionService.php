<?php

namespace App\Services\Api;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAddress;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function App\Services\getSetting;

class TransactionService
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $transactions = Transaction::when($request->headers->has('X-Mock-Status'), function ($q) use ($request) {
                return $q->where('status', $request->headers->get('X-Mock-Status'));
            })
                ->latest()
                ->get();

            return ['success' => true, 'data' => $transactions];
        } catch (\Exception $exception) {
            \Log::error('Transaction fetch failed: ' . $exception->getMessage());

            return ['success' => false, 'message' => 'An error occurred while fetching transactions. Please try again.'];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data)
    {
        try {
            $data['status'] = 'accepted';

            $transaction = Transaction::create($data);

            return ['success' => true, 'data' => $transaction];
        } catch (\Exception $exception) {
            \Log::error('Transaction creation failed: ' . $exception->getMessage());

            return ['success' => false, 'message' => 'An error occurred while storing the transaction. Please try again.'];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $data, Transaction $transaction)
    {
        try {
            $transaction->update($data);

            return ['success' => true, 'data' => $transaction];
        } catch (\Exception $exception) {
            \Log::error('Transaction update failed: ' . $exception->getMessage());

            return ['success' => false, 'message' => 'An error occurred while updating the transaction. Please try again.'];
        }
    }
}

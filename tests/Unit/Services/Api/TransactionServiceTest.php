<?php

namespace Tests\Unit\Services\Api;

use App\Models\User;
use Tests\TestCase;
use App\Models\Transaction;
use App\Services\Api\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    // Add any necessary setup or traits

    public function test_can_get_list_of_transactions()
    {
        // Create some transactions in the database for testing
        Transaction::factory()->count(3)->create();

        // Create an instance of the TransactionService
        $transactionService = new TransactionService();

        // Call the index method
        $result = $transactionService->index(request());

        // Assert that the result has a success key
        $this->assertTrue($result['success']);

        // Assert that the result has a data key with an array of transactions
        $this->assertIsArray($result['data']);
        $this->assertInstanceOf(Transaction::class, $result['data'][0]);
    }

    public function test_can_store_transaction()
    {
        // Create an user in the database for testing
        $user = User::factory()->create();

        // Create necessary data for the store method
        $data = [
            'amount' => 100.00,
            'user_id' => $user->id,
        ];

        // Create an instance of the TransactionService
        $transactionService = new TransactionService();

        // Call the store method
        $result = $transactionService->store($data);

        // Assert that the result has a success key
        $this->assertTrue($result['success']);

        // Assert that the result has a data key with the stored transaction
        $this->assertInstanceOf(Transaction::class, $result['data']);
    }

    public function test_can_update_transaction_status()
    {
        // Create a transaction in the database for testing
        $transaction = Transaction::factory()->create();

        // Create an instance of the TransactionService
        $transactionService = new TransactionService();

        // Call the update method
        $result = $transactionService->update(['status' => 'accepted'], $transaction);

        // Assert that the result has a success key
        $this->assertTrue($result['success']);

        // Assert that the result has a data key with the updated transaction
        $this->assertInstanceOf(Transaction::class, $result['data']);

        // Assert that the transaction in the database has been updated
        $this->assertEquals('accepted', $transaction->fresh()->status);
    }
}

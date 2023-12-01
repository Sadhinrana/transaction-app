<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    // Add any necessary setup or traits

    public function test_can_get_list_of_transactions()
    {
        // Create some transactions in the database for testing
        Transaction::factory()->count(3)->create();

        // Make a request to the index endpoint
        $response = $this->get('/api/transactions');

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'status',
                    'transaction_id',
                    'amount',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
            ],
            'message',
        ]);
    }

    public function test_can_store_transaction()
    {
        // Create an user in the database for testing
        $user = User::factory()->create();

        // Create necessary data for the request
        $data = [
            'amount' => 100.00,
            'user_id' => $user->id,
        ];

        // Make a request to the store endpoint
        $response = $this->post('/api/transactions', $data);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'status',
                'transaction_id',
                'amount',
                'user_id',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);
    }

    public function test_can_update_transaction_status()
    {
        // Create a transaction in the database for testing
        $transaction = Transaction::factory()->create();

        // Make a request to the update endpoint
        $response = $this->put("/api/transactions/{$transaction->id}", [
            'status' => 'accepted',
            'transaction_id' => $transaction->transaction_id,
        ]);

        // Assert that the response has a successful status code
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'status',
                'transaction_id',
                'amount',
                'user_id',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);
    }
}

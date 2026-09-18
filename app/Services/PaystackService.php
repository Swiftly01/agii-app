<?php
// app/Services/PaystackService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $client;
    protected $secretKey;
    protected $publicKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->secretKey = config('paystack.secret_key');
        $this->publicKey = config('paystack.public_key');
        $this->baseUrl = 'https://api.paystack.co';
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction(array $data)
    {
        try {
            $response = $this->client->post($this->baseUrl . '/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result['status'] === true) {
                return [
                    'success' => true,
                    'authorization_url' => $result['data']['authorization_url'],
                    'access_code' => $result['data']['access_code'],
                    'reference' => $result['data']['reference']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to initialize transaction'
            ];
        } catch (\Exception $e) {
            Log::error('Paystack initialization error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment service unavailable. Please try again.'
            ];
        }
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction($reference)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/transaction/verify/' . $reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return $result;
        } catch (\Exception $e) {
            Log::error('Paystack verification error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Verification failed'
            ];
        }
    }

    /**
     * Create a subscription plan
     */
    public function createPlan(array $data)
    {
        try {
            $response = $this->client->post($this->baseUrl . '/plan', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Paystack plan creation error: ' . $e->getMessage());
            return ['status' => false];
        }
    }

    /**
     * Generate a unique reference
     */
    public function generateReference()
    {
        return 'AGII_' . uniqid() . '_' . time();
    }
}

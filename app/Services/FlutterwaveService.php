<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;
    private string $tokenUrl;

    public function __construct()
    {
        $this->clientId = config('services.flutterwave.client_id');
        $this->clientSecret = config('services.flutterwave.client_secret');
        $this->baseUrl = config('services.flutterwave.base_url');
        $this->tokenUrl = config('services.flutterwave.token_url');
    }

public function getAccessToken(): string
{
  $response = Http::withOptions([
    'version' => '1.1',
    'connect_timeout' => 20,
    'timeout' => 30,
])->asForm()->post($this->tokenUrl, [
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
        'grant_type' => 'client_credentials',
    ]);

    $response->throw();

    return $response->json('access_token');}
    public function createCustomer(string $email): array
{
    $response = Http::withToken($this->getAccessToken())
       ->timeout(35)
       ->connectTimeout(15)

        ->withHeaders([
            'X-Trace-Id' => (string) \Illuminate\Support\Str::uuid(),
            'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
        ])
        ->post($this->baseUrl . '/customers', [
            'email' => $email,
        ]);

    $response->throw();

    return $response->json();
}
public function createMobileMoneyPaymentMethod(
    string $countryCode,
    string $network,
    string $phoneNumber
): array

{
    $response = Http::withToken($this->getAccessToken())
    ->timeout(30)
    ->timeout(15)
        ->withHeaders([
            'X-Trace-Id' => (string) \Illuminate\Support\Str::uuid(),
            'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
        ])
        ->post($this->baseUrl . '/payment-methods', [
            'type' => 'mobile_money',
            'mobile_money' => [
                'country_code' => $countryCode,
                'network' => $network,
                'phone_number' => $phoneNumber,
            ],
        ]);

    $response->throw();

    return $response->json();
}
public function createCharge(
    string $customerId,
    string $paymentMethodId,
    int|float $amount,
    string $currency = 'XAF',
    ?string $redirectUrl = null
): array
{
    $reference = 'PAY-' . strtoupper(\Illuminate\Support\Str::random(12));

    $response = Http::withToken($this->getAccessToken())
        ->withHeaders([
            'X-Trace-Id' => (string) \Illuminate\Support\Str::uuid(),
            'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
            'X-Scenario-Key' => 'scenario:auth_redirect',
        ])
        ->post($this->baseUrl . '/charges', [
            'amount' => $amount,
            'currency' => $currency,
            'reference' => $reference,
            'customer_id' => $customerId,
            'payment_method_id' => $paymentMethodId,
            'redirect_url' => $redirectUrl,
        ]);

    $response->throw();

    return $response->json();
}
public function verifyCharge(string $chargeId): array
{
    $response = Http::withToken($this->getAccessToken())
        ->timeout(35)
        ->connectTimeout(15)
        ->withHeaders([
            'X-Trace-Id' => (string) \Illuminate\Support\Str::uuid(),
        ])
        ->get($this->baseUrl . '/charges/' . $chargeId);

    $response->throw();

    return $response->json();
}
public function getCharge(string $chargeId): array
{
    $response = Http::withToken($this->getAccessToken())
        ->withHeaders([
            'X-Trace-Id' => (string) \Illuminate\Support\Str::uuid(),
        ])
        ->get($this->baseUrl . '/charges/' . $chargeId);

    $response->throw();

    return $response->json();
}
}
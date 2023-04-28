<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\NMIGateway;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentGatewayTest extends TestCase
{
    /** @test */
    public function it_returns_an_error_if_a_required_field_is_missing()
    {
        Http::post('https://secure.networkmerchants.com/api/transact.php', [
            'amount' => '100.00',
            'cardNumber' => '4111111111111111',
            'month' => '01',
            'year' => '2025',
            'cvv' => '123',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip' => '90210'
        ]);
    }

    /** @test */
    public function it_processes_a_payment_successfully()
    {
        $request = [
            'amount' => '100.00',
            'cardNumber' => '4111111111111111',
            'month' => '01',
            'year' => '2025',
            'cvv' => '123',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip' => '90210'
        ];

        $response = $this->gateway->processPayment($request);
        $this->assertArrayHasKey('transactionId', $response);
    }
}

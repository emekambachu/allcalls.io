<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DatalotResponseController extends Controller
{
    public function store(Request $request)
    {
        $xmlPayload = <<<XML
<contact_create>
    <access_key>+kr1zoq6dy</access_key>
    <source_id>14660</source_id>
    <product_id>227</product_id>
    <campaign_id>medsupinbound1avl</campaign_id>
    <is_medsup>true</is_medsup>
    <pass_through></pass_through>
    <contact_permission>
        <lead_id></lead_id>
        <phone_explicit>true</phone_explicit>
        <mobile_explicit>true</mobile_explicit>
        <autodial_explicit>true</autodial_explicit>
        <form_url></form_url>
        <legal_language></legal_language>
        <spec>phone_only</spec>
    </contact_permission>
    <contact_info>
        <general_info>
            <city></city>
            <state></state>
            <zip_code>23116</zip_code>
            <gender></gender>
            <dob_year>1950</dob_year>
            <dob_month>05</dob_month>
            <dob_day>12</dob_day>
            <datetime_collected></datetime_collected>
            <ip_address></ip_address>
            <user_agent></user_agent>
            <preferred_phone>M</preferred_phone>
        </general_info>
        <product_info>
            <age>70</age>
            <is_smoker></is_smoker>
            <is_insured></is_insured>
            <height_feet></height_feet>
            <height_inches></height_inches>
            <weight></weight>
            <expectant_parent></expectant_parent>
            <marital_status></marital_status>
            <employment></employment>
        </product_info>
    </contact_info>
    <test>false</test>
</contact_create>
XML;

        // Make the HTTP request
        $response = Http::withHeaders([
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml',
        ])->send('POST', 'https://api.datalot.com/contact/create/v2/pricequote', [
            'body' => $xmlPayload,
        ]);

        // Log the response body and status code
        Log::debug('api-logs:datalot: Response', [
            'body' => $response->body(),
            'status' => $response->status(),
        ]);

        // Extract the XML content from the response
        $responseBody = $response->body();
        $xmlStartPos = strpos($responseBody, '<?xml');
        $xmlString = false;
        if ($xmlStartPos !== false) {
            $xmlString = substr($responseBody, $xmlStartPos);
        }

        if ($xmlString) {
            // Parse the XML string
            $xml = simplexml_load_string($xmlString);

            if ($xml !== false && isset($xml->quote->price)) {
                // Extract the price
                $price = (string) $xml->quote->price;
                Log::debug('api-logs:datalot: Price extracted', ['price' => $price]);
            } else {
                Log::error('Failed to parse XML or price tag not found');
            }
        } else {
            Log::error('No XML content found in the response');
        }

        // Return based on the response success
        return $response->successful() ? 'TRUE' : 'FALSE';
    }
}

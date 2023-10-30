<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CallCenterDispositionAPIController extends Controller
{
    /**
     * Update disposition based on callerId.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Log::debug('api-logs:call-center-disposition: Request Data', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'query_string' => $request->getQueryString(),
        ]);

        // Validate the incoming request data
        $data = $request->validate([
            'callerId' => 'required|string',
            'disposition' => 'required|string|in:' . implode(',', $this->getValidDispositions()),
        ]);

        // Perform a lookup using the callerId to get the contactID
        $contactId = $this->getContactIdByCallerId($data['callerId']);

        Log::debug('api-logs:call-center-disposition: Fetched Contact ID', ['contactId' => $contactId]);

        // If no contactId is found, return an error response
        if (!$contactId) {
            return response()->json(['error' => 'Contact ID not found for the given callerId'], 405);
        }

        // Update the disposition in the dialer
        $updateResult = $this->updateDialerDisposition($contactId, $data['disposition']);

        // Check if the update was successful
        if ($updateResult) {
            Log::debug('api-logs:call-center-disposition: Successfully updated disposition', ['contactId' => $contactId, 'disposition' => $data['disposition']]);
            return response()->json(['success' => 'Disposition updated successfully'], 201);
        }

        Log::debug('api-logs:call-center-disposition: Failed to update disposition', ['contactId' => $contactId, 'disposition' => $data['disposition']]);
        return response()->json(['error' => 'Failed to update disposition'], 501);
    }

    /**
     * Dummy method to get contactId by callerId.
     * 
     * @param  string $callerId
     * @return int|null
     */
    public function getContactIdByCallerId($callerId) {
        // API details
        $username = env('DIALER_AI_USERNAME');
        $password = env('DIALER_AI_PASSWORD');
        $hostname = env('DIALER_AI_BASE_DOMAIN');

        $url = "http://{$hostname}/rest-api/contact/?contact={$callerId}";
    
        Log::debug('api-logs:call-center-disposition: Initiating API call.', ['callerId' => $callerId, 'url' => $url]);
    
        // Make a GET request using the Http facade
        $response = Http::withBasicAuth($username, $password)->get($url);
    
        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['results'][0]['url'])) {
                // Extract contact ID from the URL
                preg_match('#/contact/(\d+)/#', $data['results'][0]['url'], $matches);
                $contactId = $matches[1] ?? null;
    
                Log::debug('api-logs:call-center-disposition: Contact ID retrieved successfully.', ['contactId' => $contactId]);
                return $contactId;
            } else {
                Log::debug('api-logs:call-center-disposition: Contact ID not found in the response.');
            }
        } else {
            Log::debug('api-logs:call-center-disposition: API request failed.', ['response' => $response->body()]);
        }
    
        return null;
    }
    

    /**
     * Dummy method to update disposition in the dialer.
     * 
     * @param  int    $contactId
     * @param  string $disposition
     * @return bool
     */
    protected function updateDialerDisposition(int $contactId, string $disposition): bool
    {
        // Here, you would make the API call to the dialer to update the disposition for the given contactId
        // For the sake of this example, we'll return a dummy result
        return true; // This is a dummy value; replace with the actual logic
    }

    /**
     * Get valid dispositions.
     * 
     * @return array
     */
    protected function getValidDispositions(): array
    {
        return [
            'Call back requested', 'Do not call', 'Do not call escalate', 'Early Hang Up', 'Hung up',
            'Hung Up Pitched', 'IVR removal', 'Language Barrier', 'No Disposition', 'NO RESPONSE',
            'Not available', 'Not interested', 'NQ NI Auto Insurance', 'NQ no income', 'NQ no Insurance',
            'NQ No vehicle', 'ST-Above 40K 19-64 Yrs Old', 'ST-Below 40K 19-55 Yrs Old', 'ST-Below 40K 56-64 Yrs Old',
            'Successful Transfer- Auto Insurance', 'TC-Above 40K 19-64 Yrs Old', 'TC-Below 40K 19-55 Yrs Old',
            'TC-Below 40K 56-64 Yrs Old', 'TD-Above 40K 19-64 Yrs Old', 'TD-Below 40K 19-55 Yrs Old',
            'TD-Below 40K 56-64 Yrs Old', 'TIP-Above 40K 19-64 Yrs Old', 'TIP-Below 40K 19-55 Yrs Old',
            'TIP-Below 40K 56-64 Yrs Old', 'Transfer Connected- Auto Insurance', 'Transfer Declined- Auto Insurance',
            'Transfer in Progress- Auto Insurance', 'Voicemail', 'Wrong number'
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\SignHere;
use Illuminate\Support\Facades\Log;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Model\EnvelopeDefinition;

class DocusignExampleController extends Controller
{
    public function index()
    {
        $rsaPrivateKey = file_get_contents(base_path('docusign_private.key'));
        $integration_key = env('DOCUSIGN_INTEGRATION_KEY');
        $impersonatedUserId = env('DOCUSIGN_USER_ID');
        $scopes = "signature impersonation";

        $config = new Configuration();
        $apiClient = new ApiClient($config);

        $apiClient->getOAuth()->setOAuthBasePath(env('DOCUSIGN_ACCOUNT_BASE_URI'));
        $response = $apiClient->requestJWTUserToken($integration_key, $impersonatedUserId, $rsaPrivateKey, $scopes);
        
        Log::debug('Response: ' . json_encode($response));

        $access_token = $response[0]['access_token'];

        Log::debug('Access Token: ' . $access_token);
        
        $config->addDefaultHeader('Authorization', 'Bearer ' . $access_token);

        // Create the document
        $documentContent = file_get_contents(asset('/first-step-sign.pdf'));
        $document = new Document([
            'document_base64' => base64_encode($documentContent),
            'name' => 'sample.pdf',
            'file_extension' => 'pdf',
            'document_id' => '1'
        ]);

        // Create the signer
        $signer = new Signer([
            'email' => 'example@allcalls.io',
            'name' => 'AllCalls User',
            'recipient_id' => '1',
        ]);

        // Create the signature tab
        $signHere = new SignHere([
            'document_id' => '1',
            'page_number' => '1',
            'recipient_id' => '1',
            'anchor_string' => 'Sign here...',
            'anchor_units' => 'cms',
            'anchor_x_offset' => '0',
            'anchor_y_offset' => '0'
        ]);

        $signer->setTabs(new Tabs(['sign_here_tabs' => [$signHere]]));

        // Combine everything into an envelope definition
        $envelopeDefinition = new EnvelopeDefinition([
            'email_subject' => 'Please sign this document',
            'documents' => [$document],
            'recipients' => ['signers' => [$signer]],
            'status' => 'sent'
        ]);

        // Send the envelope
        $envelopesApi = new EnvelopesApi($apiClient);
        $envelopeSummary = $envelopesApi->createEnvelope(env('DOCUSIGN_API_ACCOUNT_ID'), $envelopeDefinition);

        return response()->json(['envelope_id' => $envelopeSummary->getEnvelopeId()]);
    }
}

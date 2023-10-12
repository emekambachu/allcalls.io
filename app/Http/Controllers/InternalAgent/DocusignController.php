<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use Exception;

class DocusignController extends Controller
{

    /** hold config value */
    private $config;

    private $signer_client_id = 1000; # Used to indicate that the signer will use embedded

    /** Specific template arguments */
    private $args;

    private $clientId;
    private $clinetSceret;
    private $URL;
    private $tokenUrl;
    private $accountId;
    private $baseUrl;

    public function __construct() {
        $this->clientId = "75d97718-8a98-4d27-8def-17c2fceed79f";
        $this->clinetSceret = "897ce745-c111-4229-aff4-6637a9a9a066";
        $this->URL = "https://account-d.docusign.com/oauth/auth?";
        $this->tokenUrl = "https://account-d.docusign.com/oauth/token";
        $this->accountId = "7716918e-104d-4915-b7ca-eff79222ac45";
        $this->baseUrl = "https://demo.docusign.net/restapi";
    }

    /**
     * Show the html page
     *
     * @return render
     */
    public function index()
    {
        return view('connect');
    }


    /**
     * Connect your application to docusign
     *
     * @return url
     */
    public function connectDocusign()
    {
        try {
            $params = [
                'response_type' => 'code',
                'scope' => 'signature',
                'client_id' => $this->clientId, //change
                'state' => 'a39fh23hnf23',
                'redirect_uri' => route('internal.agent.docusign.callback'),
            ];
            $queryBuild = http_build_query($params);

            $botUrl = $this->URL . $queryBuild;

            return response()->json([
                'success' => true,
                'route' => $botUrl,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * This function called when you auth your application with docusign
     *
     * @return url
     */
    public function callback(Request $request)
    {
        $code = $request->code;

        $client_id = $this->clientId; //change
        $client_secret = $this->clinetSceret; //change

        $integrator_and_secret_key = "Basic " . utf8_decode(base64_encode("{$client_id}:{$client_secret}"));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $post = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $headers = array();
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = "authorization: $integrator_and_secret_key";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $decodedData = json_decode($result);
        $request->session()->put('docusign_auth_code', $decodedData->access_token);
        return redirect()->route('contract.steps');
    }

    public function signDocument()
    {
        try {
            $this->args = $this->getTemplateArgs();
            $args = $this->args;
            $envelope_args = $args["envelope_args"];

            # Create the envelope request object
            $envelope_definition = $this->make_envelope($args["envelope_args"]);
            $envelope_api = $this->getEnvelopeApi();
            # Call Envelopes::create API method
            # Exceptions will be caught by the calling function

            $api_client = new \DocuSign\eSign\client\ApiClient($this->config);
            $envelope_api = new \DocuSign\eSign\Api\EnvelopesApi($api_client);
            $results = $envelope_api->createEnvelope($args['account_id'], $envelope_definition);
            $envelope_id = $results->getEnvelopeId();
            session()->put('envelope_id', $envelope_id);
            $authentication_method = 'None'; # How is this application authenticating
            # the signer? See the `authenticationMethod' definition
            # https://developers.docusign.com/esign-rest-api/reference/Envelopes/EnvelopeViews/createRecipient
            $recipient_view_request = new \DocuSign\eSign\Model\RecipientViewRequest([
                'authentication_method' => $authentication_method,
                'client_user_id' => $envelope_args['signer_client_id'],
                'recipient_id' => auth()->user()->id,
                'return_url' => $envelope_args['ds_return_url'],
                'user_name' => auth()->user()->name, 'email' => auth()->user()->email
            ]);

            $results = $envelope_api->createRecipientView($args['account_id'], $envelope_id, $recipient_view_request);

            return redirect()->to($results['url']);
        } catch (Exception $e) {
            dd($e);
        }
    }


    private function make_envelope($args)
    {
        $filename = 'World_Wide_Corp_lorem.pdf';
        $demo_docs_path = asset('/first-step-sign.pdf');

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $content_bytes = file_get_contents($demo_docs_path, false, stream_context_create($arrContextOptions));
        // dd($content_bytes);
        $base64_file_content = base64_encode($content_bytes);
        // dd($base64_file_content);
        # Create the document model
        $documentId = time().auth()->user()->id;
        $document = new \DocuSign\eSign\Model\Document([ # create the DocuSign document object
            'document_base64' => $base64_file_content,
            'name' => 'Example document', # can be different from actual file name
            'file_extension' => 'pdf', # many different document types are accepted
            'document_id' => $documentId, # a label used to reference the doc
        ]);
        session()->put('document_id', $documentId);
        
        # Create the signer recipient model
        $signer = new \DocuSign\eSign\Model\Signer([ # The signer
            'email' => auth()->user()->email, 'name' => auth()->user()->name,
            'recipient_id' => "1", 'routing_order' => "1",
            # Setting the client_user_id marks the signer as embedded
            'client_user_id' => $args['signer_client_id'],
        ]);
        # Create a sign_here tab (field on the document)
        $sign_here = new \DocuSign\eSign\Model\SignHere([ # DocuSign SignHere field/tab
            'anchor_string' => '/sn1/', 'anchor_units' => 'pixels',
            'anchor_y_offset' => '10', 'anchor_x_offset' => '20',
        ]);
        # Add the tabs model (including the sign_here tab) to the signer
        # The Tabs object wants arrays of the different field/tab types
        $signer->settabs(new \DocuSign\eSign\Model\Tabs(['sign_here_tabs' => [$sign_here]]));
        # Next, create the top level envelope definition and populate it.

        $envelope_definition = new \DocuSign\eSign\Model\EnvelopeDefinition([
            'email_subject' => "On Boarding Info",
            'documents' => [$document],
            # The Recipients object wants arrays for each recipient type
            'recipients' => new \DocuSign\eSign\Model\Recipients(['signers' => [$signer]]),
            'status' => "sent", # requests that the envelope be created and sent.
        ]);

        return $envelope_definition;
    }

    /**
     * Getter for the EnvelopesApi
     */
    public function getEnvelopeApi(): EnvelopesApi
    {
        $this->config = new Configuration();
        $this->config->setHost($this->args['base_path']);
        $this->config->addDefaultHeader('Authorization', 'Bearer ' . $this->args['ds_access_token']);
        $this->apiClient = new ApiClient($this->config);
        return new EnvelopesApi($this->apiClient);
    }

    /**
     * Get specific template arguments
     *
     * @return array
     */
    private function getTemplateArgs()
    {
        $envelope_args = [
            'signer_client_id' => auth()->user()->id,
            'ds_return_url' => route('contract.steps')
        ];
        $args = [
            'account_id' => $this->accountId,
            'base_path' => $this->baseUrl,
            'ds_access_token' => Session::get('docusign_auth_code'),
            'envelope_args' => $envelope_args
        ];
        return $args;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Models\Contact;
use Illuminate\Support\Facades\Session;


class HubSpotController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('HUBSPOT_CLIENT_ID');
        $this->clientSecret = env('HUBSPOT_CLIENT_SECRET');
        $this->redirectUri = env('HUBSPOT_REDIRECT_URI');
    }

    public function redirectToHubSpot()
    {
        $authUrl = "https://app.hubspot.com/oauth/authorize?client_id={$this->clientId}&redirect_uri={$this->redirectUri}&scope=contacts";
        return Redirect::to($authUrl);
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
   
        if ($code) {
            $payload = [
                'grant_type' => 'authorization_code',
                'client_id' => env('HUBSPOT_CLIENT_ID'),
                'client_secret' => env('HUBSPOT_CLIENT_SECRET'),
                'redirect_uri' => env('HUBSPOT_REDIRECT_URI'),
                'code' => $code,
            ];
    
            \Log::info('Payload for token exchange:', $payload);
    
            $response = Http::asForm()->post('https://api.hubapi.com/oauth/v1/token', $payload);
    
            \Log::info('HubSpot Token Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'] ?? null;
                $refreshToken = $data['refresh_token'] ?? null;
    
                if ($accessToken && $refreshToken) {
                    Session::put('hubspot_access_token', $accessToken);
                    Session::put('hubspot_refresh_token', $refreshToken);
    
                    return redirect('/contacts')->with('success', 'Authorization successful!');
                } else {
                    return redirect('/')->withErrors(['message' => 'Access token or refresh token is null']);
                }
            } else {
                \Log::error('Failed to exchange authorization code for access token:', $response->json());
                return redirect('/')->withErrors(['message' => 'Failed to get access token']);
            }
        }
    
        return redirect('/')->withErrors(['message' => 'Authorization failed']);
    }
    

    public function syncContacts()
{
    $response = Http::withToken(env('HUBSPOT_ACCESS_TOKEN'))
    ->post('https://api.hubapi.com/crm/v3/objects/contacts', [
        'appId' => 3733600,
        'eventId' => 100,
        'subscriptionId' => 2770610,
        'portalId' => 47098601,
        'occurredAt' => 1723916226915,
        'subscriptionType' => 'contact.creation',
        'attemptNumber' => 0,
        'objectId' => 123,
        'changeSource' => 'CRM',
        'changeFlag' => 'NEW'
    ]);

    if ($response->successful()) {
        $contacts = $response->json()['results'];

        foreach ($contacts as $contact) {
            Contact::updateOrCreate(
                ['hubspot_id' => $contact['id']],
                [
                    'first_name' => $contact['properties']['firstname'] ?? null,
                    'last_name' => $contact['properties']['lastname'] ?? null,
                    'email' => $contact['properties']['email'] ?? null,
                ]
            );
        }

        return "Contacts synced successfully!";
    }

    return "Failed to sync contacts.";
}
public function showContacts()
{
    $contacts = Contact::paginate(10);
  
    return view('contacts.index', compact('contacts'));
}

public function showContact($id)
{
    $contact = Contact::findOrFail($id);
    
    return view('contacts.show', compact('contact'));
}
}

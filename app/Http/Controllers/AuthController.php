<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function redirectToHubSpot()
    {
        $clientId = env('HUBSPOT_CLIENT_ID');
        $redirectUri = env('HUBSPOT_REDIRECT_URI');
        $authorizationUrl = "https://app.hubspot.com/oauth/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&scope=contacts%20forms";

        return redirect($authorizationUrl);
    }

    public function handleHubSpotCallback(Request $request)
    {
        $code = $request->query('code');
        $client = new Client();

        $response = $client->post('https://api.hubapi.com/oauth/v1/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('HUBSPOT_CLIENT_ID'),
                'client_secret' => env('HUBSPOT_CLIENT_SECRET'),
                'redirect_uri' => env('HUBSPOT_REDIRECT_URI'),
                'code' => $code,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];

        // Save the access token to the .env file or database
        // For security reasons, consider storing it securely

        return 'Access token: ' . $accessToken;
    }
}
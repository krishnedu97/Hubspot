<?php 
namespace App\Services;

use GuzzleHttp\Client;

class HubSpotService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.hubapi.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('HUBSPOT_ACCESS_TOKEN'),
            ],
        ]);
    }

    public function getContacts()
    {
        $response = $this->client->get('crm/v3/objects/contacts');
        return json_decode($response->getBody()->getContents(), true)['results'];
    }

    public function getContactById($id)
    {
        $response = $this->client->get("crm/v3/objects/contacts/{$id}");
        return json_decode($response->getBody()->getContents(), true);
    }
}

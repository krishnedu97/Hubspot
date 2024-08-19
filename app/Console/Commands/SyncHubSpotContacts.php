<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HubSpotService;
use App\Models\Contact;

class SyncHubSpotContacts extends Command
{
    protected $signature = 'sync:hubspot-contacts';
    protected $description = 'Sync contacts from HubSpot to database';

    protected $hubSpotService;

    public function __construct(HubSpotService $hubSpotService)
    {
        parent::__construct();
        $this->hubSpotService = $hubSpotService;
    }

    public function handle()
    {
        $contacts = $this->hubSpotService->getContacts();
        // dd( $contacts);
        foreach ($contacts as $hubspotContact) {
            Contact::updateOrCreate(
                ['hubspot_id' => $hubspotContact['id']],
                [
                    'first_name' => $hubspotContact['properties']['firstname'] ?? null,
                    'last_name' => $hubspotContact['properties']['lastname'] ?? null,
                    'email' => $hubspotContact['properties']['email'] ?? null,
                ]
            );
        }

        $this->info('Contacts synced successfully.');
    }
}

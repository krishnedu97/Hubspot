<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('sync:contacts', function () {
    app(\App\Http\Controllers\HubSpotController::class)->syncContacts();
})->describe('Sync contacts from HubSpot');

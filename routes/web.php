<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubSpotController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/callback', [HubSpotController::class, 'handleCallback']);
Route::get('/auth/hubspot', [HubSpotController::class, 'redirectToHubSpot']);
Route::get('/contacts', [HubSpotController::class, 'showContacts'])->name('contacts.index');
Route::get('/contacts/{id}', [HubSpotController::class, 'showContact'])->name('contacts.show');



<?php
}); // end API routes

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => ['api', 'api-throttle:api']], function () {

    Route::get('/', function () {
        return response()->json(
            [
                'status' => 'error',
                'message' => '404 endpoint not found. This is the base URL for the API and does not return anything itself. Please check the API reference at https://snipe-it.readme.io/reference to find a valid API endpoint.',
                'payload' => null,
            ], 404);
    });

    Route::withoutMiddleware(['api'])->get('/client', function () {
        $client = Client::firstOrCreate(
            ['redirect' => 'com.grokability.snipeitmobile://home'],
}); // end API routes

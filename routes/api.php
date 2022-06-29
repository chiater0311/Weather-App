<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getCurrentWeatherData', function () {
    $apikey = config('services.openWeatherMap.key');
    $cityName = request('cityName');
    $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apikey&units=metric");

    return $response->json();
});

Route::get('/getForecastData', function () {
    $apikey = config('services.openWeatherMap.key');
    $cityName = request('cityName');
    $response = Http::get("api.openweathermap.org/data/2.5/forecast?q=$cityName&appid=$apikey&cnt=6&units=metric");

    return $response->json();
});

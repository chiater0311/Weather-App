<?php

namespace Tests\Feature;

test('is api can fetch data', function () {
    $cityName = "Johor";
    $response = $this->get('/api/getCurrentWeatherData?cityName=' . $cityName);

    $response->assertStatus(200);
});

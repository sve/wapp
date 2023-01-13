<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class WeatherTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_home_endpoint(): void
    {
        $response = '{"coord":{"lon":-85.1647,"lat":34.257},"location":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"base":"stations","main":{"temp":273.26,"feels_like":270.75,"temp_min":271.42,"temp_max":274.82,"pressure":1007,"humidity":85},"visibility":10000,"wind":{"speed":2.06,"deg":360},"clouds":{"all":0},"dt":1673343920,"sys":{"type":2,"id":2009938,"country":"US","sunrise":1673354831,"sunset":1673390917},"timezone":-18000,"id":4219762,"name":"Rome","cod":200}';
        Http::fake([
            'https://api.openweathermap.org/*' => Http::response($response),
        ]);

        $user = User::factory()->create();
        $user->locations()->create([
            'name' => 'rome',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('api.home.index'));
        $response->assertJsonStructure([
            'data' => [
                'user',
                'weather'
            ],
        ]);
        $response->json('data.weather.name', 'Rome');
    }
}

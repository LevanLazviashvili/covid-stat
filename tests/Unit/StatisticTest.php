<?php

namespace Tests\Unit;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StatisticTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCanListStatisticsWithoutToken() {
        $this->json('get', 'api/statistic')
            ->assertStatus(401);
    }

    public function testCanListStatisticsWithToken()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $this->json('get', 'api/statistic')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [ 'id', 'code', 'name', 'statistics' ],
            ]);
    }

    public function testCanListSummaryWithoutToken() {
        $this->json('get', 'api/summary')
            ->assertStatus(401);
    }

    public function testCanListSummaryWithToken()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->json('get', 'api/summary')
            ->assertStatus(200)
            ->assertJsonStructure([ 'confirmed', 'death', 'recover' ]);
    }
}

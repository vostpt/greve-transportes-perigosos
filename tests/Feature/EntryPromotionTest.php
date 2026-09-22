<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\UpdateStationsCacheJob;
use App\Models\Entry;
use App\Models\FuelStation;
use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EntryPromotionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // The default seed inserts the options rows.
        $this->seed();
    }

    private function station(): FuelStation
    {
        return FuelStation::create([
            'name' => 'Test Station',
            'brand' => 'Galp',
            'lat' => 38.7,
            'long' => -9.13,
            'sell_gasoline' => true,
            'sell_diesel' => true,
            'sell_lpg' => false,
            'has_gasoline' => true,
            'has_diesel' => true,
            'has_lpg' => false,
            'district' => 'Lisboa',
            'county' => 'Lisboa',
        ]);
    }

    public function test_consensus_of_distinct_ips_promotes_entry(): void
    {
        Queue::fake();
        Option::set('num_entries_required', '3');
        $station = $this->station();

        foreach (['1.1.1.1', '2.2.2.2', '3.3.3.3', '4.4.4.4'] as $ip) {
            Entry::create([
                'fuel_station' => $station->id,
                'has_gasoline' => false,
                'has_diesel' => false,
                'has_lpg' => false,
                'ip' => $ip,
            ]);
        }

        $station->refresh();
        $this->assertFalse($station->has_gasoline);
        $this->assertFalse($station->has_diesel);
        $this->assertSame(4, Entry::query()->used()->count());
        Queue::assertPushed(UpdateStationsCacheJob::class);
    }

    public function test_below_threshold_does_not_promote(): void
    {
        Queue::fake();
        Option::set('num_entries_required', '10');
        $station = $this->station();

        foreach (['1.1.1.1', '2.2.2.2', '3.3.3.3'] as $ip) {
            Entry::create([
                'fuel_station' => $station->id,
                'has_gasoline' => false,
                'has_diesel' => false,
                'has_lpg' => false,
                'ip' => $ip,
            ]);
        }

        $station->refresh();
        $this->assertTrue($station->has_gasoline); // unchanged
        $this->assertSame(0, Entry::query()->used()->count());
        Queue::assertNotPushed(UpdateStationsCacheJob::class);
    }

    public function test_same_ip_does_not_count_towards_own_consensus(): void
    {
        Queue::fake();
        Option::set('num_entries_required', '2');
        $station = $this->station();

        foreach (range(1, 5) as $_) {
            Entry::create([
                'fuel_station' => $station->id,
                'has_gasoline' => false,
                'has_diesel' => false,
                'has_lpg' => false,
                'ip' => '1.1.1.1',
            ]);
        }

        $station->refresh();
        $this->assertTrue($station->has_gasoline);
        $this->assertSame(0, Entry::query()->used()->count());
    }
}

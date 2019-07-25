<?php
declare(strict_types=1);

namespace App\Events;

use App\Entry;
use Illuminate\Queue\SerializesModels;

class EntryCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Entry $entry)
    {
        if (Entry::lastHour()->related($entry->fuel_station, $entry->has_gasoline, $entry->has_diesel, $entry->has_lpg)->count() > 10) {
            $entry->push();
        }
    }
}

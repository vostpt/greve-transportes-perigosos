<?php
declare(strict_types=1);

namespace App\Events;

use App\Entry;
use App\Option;
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
        $required_count = \intval(Option::find('num_entries_required')->value);
        if (\is_nan($required_count)) {
            $required_count = 10;
        }
        if (Entry::lastHour()->related($entry->fuel_station, $entry->has_gasoline, $entry->has_diesel, $entry->has_lpg)->count() > $required_count) {
            $entry->push();
        }
    }
}

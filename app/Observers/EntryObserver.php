<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Entry;
use App\Models\Option;

class EntryObserver
{
    public function created(Entry $entry): void
    {
        $required = (int) (Option::get('num_entries_required') ?? '10');

        $matches = Entry::query()
            ->lastHour()
            ->related($entry->fuel_station, $entry->has_gasoline, $entry->has_diesel, $entry->has_lpg, $entry->ip)
            ->count();

        if ($matches >= $required) {
            $entry->promote();
        }
    }
}

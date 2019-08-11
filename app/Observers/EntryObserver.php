<?php
declare(strict_types=1);

namespace App\Observers;

use App\Entry;
use App\Option;

class EntryObserver
{
    /**
     * Handle the entry "created" event.
     *
     * @param  \App\Entry  $entry
     * @return void
     */
    public function created(Entry $entry)
    {
        $required_count = \intval(Option::find('num_entries_required')->value);
        if (\is_nan($required_count)) {
            $required_count = 10;
        }
        if (Entry::lastHour()->related($entry->fuel_station, $entry->has_gasoline, $entry->has_diesel, $entry->has_lpg, $entry->ip)->count() >= $required_count) {
            $entry->push();
        }
    }

    /**
     * Handle the entry "updated" event.
     *
     * @param  \App\Entry  $entry
     * @return void
     */
    public function updated(Entry $entry)
    {
        //
    }

    /**
     * Handle the entry "deleted" event.
     *
     * @param  \App\Entry  $entry
     * @return void
     */
    public function deleted(Entry $entry)
    {
        //
    }

    /**
     * Handle the entry "restored" event.
     *
     * @param  \App\Entry  $entry
     * @return void
     */
    public function restored(Entry $entry)
    {
        //
    }

    /**
     * Handle the entry "force deleted" event.
     *
     * @param  \App\Entry  $entry
     * @return void
     */
    public function forceDeleted(Entry $entry)
    {
        //
    }
}

<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Brands that reject anonymous public submissions
    |--------------------------------------------------------------------------
    |
    | Legacy behaviour (see EntriesController legacy line 49): the public
    | submission endpoint short-circuits without recording an entry when the
    | target station belongs to one of these brands. These brands operate their
    | own real-time supply feeds and did not want crowd-sourced data mixed in.
    |
    */
    'submission_blacklist' => [
        'Prio',
        'OZ Energia',
        'Ecobrent',
        'Bxpress',
        'Tfuel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Snapshot filesystem disk
    |--------------------------------------------------------------------------
    |
    | Where the pre-computed JSON snapshots (cache.json, stats_*.json, etc.)
    | are written. Defaults to the public disk so the front-end can fetch them
    | directly via /storage/data/*.
    |
    */
    'snapshot_disk' => env('FUEL_SNAPSHOT_DISK', 'public'),

];

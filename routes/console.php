<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('queue:work --queue=notifications,default --stop-when-empty --max-time=50 --tries=3')
    ->everyMinute()
    ->withoutOverlapping(10);


Artisan::command('users:dedupe-phones {--dry-run : Show what would change} {--delete : Delete duplicate users instead of nulling their phone}', function () {
    $dryRun = $this->option('dry-run');
    $delete = $this->option('delete');

    $dupes = DB::table('users')
        ->select('phone', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as total'))
        ->whereNotNull('phone')
        ->where('phone', '<>', '')
        ->groupBy('phone')
        ->havingRaw('COUNT(*) > 1')
        ->get();

    if ($dupes->isEmpty()) {
        $this->info('No duplicate phone numbers found.');
    } else {
        $affected = 0;

        foreach ($dupes as $d) {
            $ids = DB::table('users')
                ->where('phone', $d->phone)
                ->where('id', '<>', $d->keep_id)
                ->pluck('id');

            $affected += $ids->count();
            $this->line("{$d->phone}: keep #{$d->keep_id}, " . ($delete ? 'delete' : 'null phone on') . ' #' . $ids->implode(', #'));
        }

        $this->warn("{$dupes->count()} duplicated numbers, {$affected} accounts affected.");

        if (! $dryRun && $this->confirm('Apply these changes? Have you backed up the users table?')) {
            DB::transaction(function () use ($dupes, $delete) {
                foreach ($dupes as $d) {
                    $query = DB::table('users')
                        ->where('phone', $d->phone)
                        ->where('id', '<>', $d->keep_id);

                    $delete ? $query->delete() : $query->update(['phone' => null]);
                }
            });
            $this->info('Done.');
        }
    }

    // Blank phones also block the unique index, so convert them to NULL
    if (! $dryRun) {
        $blank = DB::table('users')->where('phone', '')->update(['phone' => null]);
        $this->info("Converted {$blank} blank phones to NULL.");
    }
})->purpose('Remove duplicate phone numbers so a unique index can be added');

<?php

namespace Kreitje\LaravelConsoleLogger\Commands;

use Illuminate\Console\Command;

class CleanupLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'console-logger:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge old records in the console log table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = config('commandlogger.days_to_keep', 60);
        $cleanupDate = now()->subDays($days)->setTime(0, 0, 0);

        $this->info('Cleaning up entries older than ' . $cleanupDate->format('d/m/yy'));
        \DB::table(config('commandlogger.table'))
            ->where('created_at', '<', $cleanupDate)
            ->delete();
    }
}

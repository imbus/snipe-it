<?php

namespace App\Console\Commands;

use App\Models\Actionlog;
use Illuminate\Console\Command;

class RemoveInvalidUploadDeleteActionLogItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snipeit:remove-invalid-upload-delete-action-log-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invalidLogs = Actionlog::query()
            ->where('action_type', 'upload deleted')
            ->whereNull('filename')
            ->get();

        $this->info("{$invalidLogs->count()} invalid log items found.");


        if ($invalidLogs->count() > 0 && $this->confirm("Do you wish to remove {$invalidLogs->count()} log items?")) {
            $invalidLogs->each(fn($log) => $log->delete());
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Resident;
use Illuminate\Console\Command;
use App\Services\ProfileSyncService;

class SyncResidentProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-resident-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update profile records from existing residents, guests and users';

    /**
     * Execute the console command.
     */
    public function handle(ProfileSyncService $syncService)
    {
        $this->info("Starting Resident → Profile sync...");

        Resident::chunk(100, function($residents) use ($syncService) {
            foreach ($residents as $resident) {
                $syncService->syncFromResident($resident);
            }
        });

        $this->info("Sync Completed Successfully.");
        return 0;
    }


    // php artisan profiles:sync-residents

}

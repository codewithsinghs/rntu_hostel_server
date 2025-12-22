<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:sync-permissions';
    protected $signature = 'permissions:sync';
   
    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Sync all route permissions into database';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $count = 0;

        foreach ($routes as $route) {
            $action = $route->getAction();

            if (isset($action['middleware'])) {
                foreach ((array) $action['middleware'] as $middleware) {
                    if (strpos($middleware, 'checkAccess:') === 0) {
                        $permission = str_replace('checkAccess:', '', $middleware);

                        if (!Permission::where('name', $permission)->exists()) {
                            Permission::create(['name' => $permission]);
                            $this->info("Created permission: {$permission}");
                            $count++;
                        }
                    }
                }
            }
        }

        $this->info("✅ Synced {$count} new permissions.");
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Faculty;
use Illuminate\Console\Command;

class GenerateFacultyCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:generate-faculty-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    protected $signature = 'faculties:generate-codes {--force}';
    protected $description = 'Generate codes for existing faculties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating faculty codes...');

        Faculty::whereNull('code')
            ->orWhere('code', '')
            ->chunk(100, function ($faculties) {

                foreach ($faculties as $faculty) {

                    $prefix = strtoupper(
                        substr(
                            preg_replace('/[^A-Za-z]/', '', $faculty->name),
                            0,
                            3
                        )
                    ) ?: 'FAC';

                    $faculty->code =
                        $prefix . str_pad($faculty->id, 4, '0', STR_PAD_LEFT);

                    $faculty->saveQuietly();

                    $this->line("Updated: {$faculty->id} → {$faculty->code}");
                }
            });

        $this->info('Faculty codes generated successfully.');
    }
}

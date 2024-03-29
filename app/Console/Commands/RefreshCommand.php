<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(app()->isProduction()) {
            $this->error("Available only on local development.");

            return self::FAILURE;
        };

        $this->call('migrate:fresh', [
            '--seed' => true
        ]);
        Storage::deleteDirectory('images/products');


    }
}

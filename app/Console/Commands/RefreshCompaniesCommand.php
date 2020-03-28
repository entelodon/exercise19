<?php

namespace App\Console\Commands;

use App\Jobs\RefreshCompaniesJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RefreshCompaniesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use this command, to update the companies using the external API as a seeder.';

    public function handle()
    {
        RefreshCompaniesJob::dispatch()->delay(Carbon::now()->addSeconds(env('SEEDER_AUTOUPDATE_FREQUENCY')));
        $this->line("Next automatic refresh in " . env('SEEDER_AUTOUPDATE_FREQUENCY') . " seconds from now. (Jobs are future to executed automatically.)");
    }
}

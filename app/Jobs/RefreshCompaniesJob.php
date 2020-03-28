<?php

namespace App\Jobs;

use App\Company\Services\CompanyUpdateService;
use App\Exceptions\InvalidDataStructure;
use App\Exceptions\InvalidEndpoint;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshCompaniesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::error("hi");
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $companyUpdateService = new CompanyUpdateService(
                env("SEEDER_ENDPOINT"),
                env("SEEDER_METHOD"),
                env("SEEDER_STRUCTURE"),
                true,
                true
            );
            $companyUpdateService->insertOrUpdateData();

            //TODO: NOTE that usually I would use cron jobs, but this is much easier for testing and will not require any system setup.
            RefreshCompaniesJob::dispatch()->delay(Carbon::now()->addSeconds(env('SEEDER_AUTOUPDATE_FREQUENCY')));
        } catch (InvalidDataStructure $e) {
            $this->fail($e);
        } catch (InvalidEndpoint $e) {
            $this->fail($e);
        }
    }
}

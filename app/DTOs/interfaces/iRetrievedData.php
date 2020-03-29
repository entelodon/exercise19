<?php

namespace App\DTOs\interfaces;

use App\Company\Models\Company;
use App\DTOs\ReportDTO;
use Carbon\Carbon;

interface iRetrievedData
{

    public function __construct(Company $company, iStructuredData $structuredData, Carbon $startDate, Carbon $endDate, string $startDateFormatted, string $endDateFormatted);

    public function getStructuredData(): iStructuredData;

    public function getCompany(): Company;

    public function getStartDate(): Carbon;

    public function getEndDate(): Carbon;

    public function getStartDateFormatted(): string;

    public function getEndDateFormatted(): string;

    public function getReportData():ReportDTO;
}

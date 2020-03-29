<?php


namespace App\DTOs;


use App\Company\Models\Company;
use App\DTOs\interfaces\iRetrievedData;
use App\DTOs\interfaces\iStructuredData;
use Carbon\Carbon;

class RetrievedDataDTO implements iRetrievedData
{
    /**
     * @var iStructuredData $structuredData
     */
    private $structuredData;

    /**
     * @var Company $company
     */
    private $company;

    /**
     * @var Carbon $startDate
     */
    private $startDate;

    /**
     * @var Carbon $endDate
     */
    private $endDate;

    /**
     * @var string $startDateFormatted
     */
    private $startDateFormatted;

    /**
     * @var string $endDateFormatted
     */
    private $endDateFormatted;

    /**
     * @var ReportDTO $reportData
     */
    private $reportData;

    public function __construct(Company $company, iStructuredData $structuredData, Carbon $startDate, Carbon $endDate, string $startDateFormatted, string $endDateFormatted)
    {
        /**
         * By the book all this should be done by getters and setters,
         * so future changes could be easier, without changing the whole signature of the class
         */
        $this->company = $company;
        $this->structuredData = $structuredData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->startDateFormatted = $startDateFormatted;
        $this->endDateFormatted = $endDateFormatted;
        $this->reportData = new ReportDTO($structuredData);
    }

    /**
     * @return iStructuredData
     */
    public function getStructuredData(): iStructuredData
    {
        return $this->structuredData;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    /**
     * @return Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function getStartDateFormatted(): string
    {
        return $this->startDateFormatted;
    }

    /**
     * @return string
     */
    public function getEndDateFormatted(): string
    {
        return $this->endDateFormatted;
    }

    /**
     * @return ReportDTO
     */
    public function getReportData(): ReportDTO
    {
        return $this->reportData;
    }

}

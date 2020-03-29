<?php

namespace App\Company\Controllers;

use App\Company\Models\Company;
use App\Company\Requests\GetCompanyDataRequest;
use App\Company\Services\CompanyService;
use App\Company\Services\DataRetrieveService;
use App\DTOs\interfaces\iRetrievedData;
use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * @var CompanyService $service
     */
    private $service;

    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    public function index(){
        return view("welcome")->with('companies', $this->service->getAllCompanies());
    }

    /**
     * @param GetCompanyDataRequest $companyDataRequest
     * @param DataRetrieveService $dataRetrieveService
     * @throws \App\Exceptions\DataRetrievingError
     * @throws \App\Exceptions\InvalidTimezone
     */
    public function generateReport(GetCompanyDataRequest $companyDataRequest, DataRetrieveService $dataRetrieveService){
        $dateFrom = DateHelper::getDateAsCarbon($companyDataRequest->dateFrom);
        $dateTo = DateHelper::getDateAsCarbon($companyDataRequest->dateTo);
        $email = $companyDataRequest->email;
        $company = $this->service->findCompanyBySymbol($companyDataRequest->symbol);

        $retrievedData = $this->getData($company, $dateFrom, $dateTo, $dataRetrieveService);

        $this->sendEmail($retrievedData, $email, $dataRetrieveService);

        return view('report')->with('retrievedData', $retrievedData);
    }

    /**
     * @param Company $company
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @param DataRetrieveService $dataRetrieveService
     * @return iRetrievedData
     * @throws \App\Exceptions\DataRetrievingError
     */
    private function getData(Company $company, Carbon $dateFrom, Carbon $dateTo, DataRetrieveService $dataRetrieveService):iRetrievedData{
        return $dataRetrieveService->getData(
            $company,
            $dateFrom,
            $dateTo
        );
    }

    /**
     * @param iRetrievedData $retrievedData
     * @param string $email
     * @param DataRetrieveService $dataRetrieveService
     */
    private function sendEmail(iRetrievedData $retrievedData, string $email, DataRetrieveService $dataRetrieveService){
        $dataRetrieveService->sendEmail($retrievedData, $email);
    }
}

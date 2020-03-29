<?php


namespace App\Company\Services;

use App\Company\Models\Company;
use App\DTOs\interfaces\iRetrievedData;
use App\DTOs\RetrievedDataDTO;
use App\Exceptions\DataRetrievingError;
use App\Exceptions\InvalidDataStructure;
use App\Exceptions\InvalidEndpoint;
use App\Helpers\CsvHelper;
use App\Helpers\DateHelper;
use App\Mail\PricesEmail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Mail;

class DataRetrieveService
{
    /**
     * @var Uri $endpoint
     */
    private $endpoint;

    /**
     * @var string $method
     */
    private $method;

    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var string $dataStructure
     */
    private $dataStructure;

    /**
     * @var string $defaultOrder
     */
    private $defaultOrder;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * @var array $dateFormat
     */
    private $dateFormat;

    /**
     * CompanyUpdateService constructor.
     * @param string $endpoint
     * @param string $method
     * @param string $dataStructure
     * @param bool $clearPrevious
     * @param bool $autoSave
     * @throws InvalidDataStructure
     * @throws InvalidEndpoint
     */
    public function __construct(string $endpoint, string $method, string $dataStructure)
    {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidEndpoint("The endpoint provided into the CompanyUpdateService constructor is not valid.");
        }

        switch ($dataStructure) {
            //Supported data structures go here
            case "csv":
                $this->dataStructure = $dataStructure;
                break;
            default:
                throw new InvalidDataStructure("The data structure provided to the CompanyUpdateService constructor is not supported or invalid.");
        }

        /*
         * Setup the configuration
         */

        /*
         * Could use parse_url instead of Uri, but since parse_url would return an array, it is better to use
         * the already implemented Uri which does exactly this, with the only difference that it parses it
         * into an object.
         */
        $this->endpoint = new Uri($endpoint);
        $this->method = $method;

        $this->settingsSetup();

        /*
         * Setup the client
         */
        $this->setupClient();
    }

    private function setupClient(): void
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri' => $this->endpoint->getScheme() . '://' . $this->endpoint->getHost()
            ]);
        }
    }


    public function getData(Company $company, Carbon $startDate, Carbon $endDate, ?string $order = null):RetrievedDataDTO{
        $startDateFormatted = DateHelper::getFormattedDate($startDate);
        $endDateFormatted = DateHelper::getFormattedDate($endDate);

        $options = [
            'query' => [
                $this->parameters['order'] => $this->defaultOrder,
                $this->parameters['startDate'] => $startDateFormatted,
                $this->parameters['endDate'] => $endDateFormatted,
            ]
        ];

        if ($order){
            $options['query']['order'] = $order;
        }

        $companySymbolHolder = Company::SYMBOL;

        $request = new Request(
            $this->method, $this->endpoint->getPath().($company->$companySymbolHolder).'.'.$this->dataStructure
        );
        $response = $this->client->send($request, $options);

        /*
         * This validation is not complete, because this is an external api,
         * for which I cannot be sure if for some weird reason would respond with a different type of 200+<300 error
         */
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new BadResponseException("Invalid response was returned", $request, $response);
        }

        if ($this->dataStructure === 'csv') {
            $structuredData = CsvHelper::getArrayFromCsvString($response->getBody()->getContents());
            return new RetrievedDataDTO($company, $structuredData, $startDate, $endDate, $startDateFormatted, $endDateFormatted);
        }

        throw new DataRetrievingError('An unhandled error occurred while retrieving the data from the specified endpoint.');
    }

    private function settingsSetup()
    {
        $this->parameters = [
            'order' => env('DATASERVER_ORDER_PARAM'),
            'startDate' => env('DATASERVER_STARTDATE_PARAM'),
            'endDate' => env('DATASERVER_ENDDATE_PARAM')
        ];

        $this->defaultOrder = env('DATASERVER_DEFAULT_ORDERING');

        $this->dateFormat = env('DATASERVER_DEFAULT_DATE_FORMAT');
    }

    public function sendEmail(iRetrievedData $retrievedData, string $to){
        /**
         * This definitely should go into queue,
         * we don't want to have timeouts due to 3rd party service dependency
         */
        $email = new PricesEmail($retrievedData);
        $email->to($to);
        $companyNameHolder = Company::NAME;
        $email->subject($retrievedData->getCompany()->$companyNameHolder);
        Mail::send($email);
    }
}

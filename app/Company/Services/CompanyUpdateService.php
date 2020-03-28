<?php


namespace App\Company\Services;

use App\Company\Models\Company;
use App\DTOs\SeederDTO;
use App\Exceptions\InvalidDataStructure;
use App\Exceptions\InvalidEndpoint;
use App\MarketCategory\Models\MarketCategory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class CompanyUpdateService
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
     * @var bool $autoSave
     */
    private $autoSave;

    /**
     * @var bool $clearPrevious
     */
    private $clearPrevious;

    /**
     * @var array $companyFields
     */
    private $companyFields;

    /**
     * @var array $marketCategoryFields
     */
    private $marketCategoryFields;

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
    public function __construct(string $endpoint, string $method, string $dataStructure, bool $clearPrevious, bool $autoSave)
    {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidEndpoint("The endpoint provided into the CompanyUpdateService constructor is not valid.");
        }

        switch ($dataStructure) {
            //Supported data structures go here
            case "json":
                $this->dataStructure = $dataStructure;
                break;
            default:
                throw new InvalidDataStructure("The data structure provided to the CompanyUpdateService constructor is not supported or invalid.");
        }

        /*
         * Setup the configuration
         */

        $this->autoSave = $autoSave;
        $this->clearPrevious = $clearPrevious;

        /*
         * Could use parse_url instead of Uri, but since parse_url would return an array, it is better to use
         * the already implemented Uri which does exactly this, with the only difference that it parses it
         * into an object.
         */
        $this->endpoint = new Uri($endpoint);
        $this->method = $method;

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

    /**
     * @return SeederDTO[]
     */
    public function insertOrUpdateData(): array
    {
        $this->settingsSetup();

        $data = $this->getData();
        if ($this->clearPrevious) {
            Company::query()->delete();
            MarketCategory::query()->delete();
        }
        return $this->mapDataIntoDtoAndSave($data, $this->companyFields, $this->marketCategoryFields);
    }

    private function getData(): array
    {
        $request = new Request(
            $this->method, $this->endpoint->getPath()
        );
        $response = $this->client->send($request);

        /*
         * This validation is not complete, because this is an external api,
         * for which I cannot be sure if for some weird reason would respond with a different type of 200+<300 error
         */
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new BadResponseException("Invalid response was returned", $request, $response);
        }
        if ($this->dataStructure === 'json') {
            return json_decode($response->getBody()->getContents(), true);
        }

        return [];
    }

    /**
     * @param array $data
     * @param array $companyFields
     * @param array $marketCategoryFields
     * @return SeederDTO[]
     */
    private function mapDataIntoDtoAndSave(array $data, array $companyFields, array $marketCategoryFields): array
    {
        return array_map(
            function (array $record) use ($companyFields, $marketCategoryFields) {
                return new SeederDTO($companyFields, $marketCategoryFields, $record, $this->autoSave);
            },
            $data
        );
    }

    private function settingsSetup()
    {
        $this->companyFields = [
            env('SEEDER_STRUCTURE_COMPANY_NAME') => Company::NAME,
            env('SEEDER_STRUCTURE_FINANCIAL_STATUS') => Company::FINANCIAL_STATUS,
            env('SEEDER_STRUCTURE_ROUND_LOT_SIZE') => Company::ROUND_LOT_SIZE,
            env('SEEDER_STRUCTURE_SECURITY_NAME') => Company::SECURITY_NAME,
            env('SEEDER_STRUCTURE_SYMBOL') => Company::SYMBOL,
            env('SEEDER_STRUCTURE_TEST_ISSUE') => Company::TEST_ISSUE,
        ];
        $this->marketCategoryFields = [
            env('SEEDER_STRUCTURE_MARKET_CATEGORY') => MarketCategory::NAME
        ];
    }
}

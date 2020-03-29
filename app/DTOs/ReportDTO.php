<?php


namespace App\DTOs;

use App\DTOs\interfaces\iStructuredData;

class ReportDTO
{
    /**
     * @var array $dates
     */
    private $dates;

    /**
     * @var array $openPrices
     */
    private $openPrices;


    /**
     * @var array $closePrices
     */
    private $closePrices;

    public function __construct(iStructuredData $retrievedData)
    {
        $this->generateReport($retrievedData);
    }

    private function generateReport(iStructuredData $retrievedData)
    {
        $dates = [];
        $openPrices = [];
        $closePrices = [];

        foreach ($retrievedData->getRows() as $row) {
            $dates[] = $row['Date'];
            $openPrices[] = $row['Open'];
            $closePrices[] = $row['Close'];
        }

        $this->openPrices = $openPrices;
        $this->closePrices = $closePrices;
        $this->dates = $dates;
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * @return array
     */
    public function getOpenPrices(): array
    {
        return $this->openPrices;
    }

    /**
     * @return array
     */
    public function getClosePrices(): array
    {
        return $this->closePrices;
    }


}

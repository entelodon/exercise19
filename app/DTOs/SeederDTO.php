<?php


namespace App\DTOs;


use App\Company\Models\Company;
use App\MarketCategory\Models\MarketCategory;

class SeederDTO
{
    /**
     * @var Company $company
     */
    private $company;

    /**
     * @var MarketCategory $marketCategory
     */
    private $marketCategory;

    /**
     * @var bool $autoSave
     */
    private $autoSave;

    /**
     * SeederDTO constructor.
     * @param array $companyFields
     * @param array $marketCategoryFields
     * @param array $record
     * @param bool $autoSave
     */
    public function __construct(array $companyFields, array $marketCategoryFields, array $record, bool $autoSave)
    {
        $this->autoSave = $autoSave;
        $this->company = new Company();

        $marketCategoryArray = [];

        foreach ($record as $fieldName => $value) {
            if (array_key_exists($fieldName, $marketCategoryFields)) {
                $marketCategoryArray[$marketCategoryFields[$fieldName]] = $value;
            }

            if (array_key_exists($fieldName, $companyFields)) {
                $this->company->setAttribute($companyFields[$fieldName], $value);
                continue; //We don't need to keep looping
            }
        }

        $this->marketCategory = $this->getOrCreateMarketCategory($marketCategoryArray);
        if($this->autoSave) {
            $this->marketCategory->companies()->save($this->company);
        }
    }

    /**
     * @param array $marketCategoryArray
     * @return MarketCategory
     */
    private function getOrCreateMarketCategory(array $marketCategoryArray): MarketCategory
    {
        /**
         * @var MarketCategory $marketCategory
         */
        $query = MarketCategory::query()->where($marketCategoryArray);
        if($this->autoSave){
            $marketCategory = $query->firstOrCreate($marketCategoryArray);
        }else{
            $marketCategory = $query->newModelInstance($marketCategoryArray);
        }
        return $marketCategory;
    }

    /**
     * @return MarketCategory
     */
    public function getMarketCategory(): MarketCategory
    {
        return $this->marketCategory;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}

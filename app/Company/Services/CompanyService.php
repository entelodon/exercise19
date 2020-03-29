<?php


namespace App\Company\Services;

use App\Company\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    /**
     * @param string $symbol
     * @param bool $failIfNotFound
     * @return Company|null
     */
    public function findCompanyBySymbol(string $symbol, bool $failIfNotFound = true):?Company{
        $query = Company::query()->where(Company::SYMBOL, $symbol);
        if($failIfNotFound){
            return $query->firstOrFail();
        }
        return $query->first();
    }

    public function getAllCompanies():Collection{
        return Company::all([Company::SYMBOL, Company::NAME, Company::SECURITY_NAME]);
    }
}

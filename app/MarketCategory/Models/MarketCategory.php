<?php

namespace App\MarketCategory\Models;

use App\Company\Models\Company;
use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    /**
     * The constants are over-abstracted,
     * the only reason this being done, is because,
     * is because the Eloquent messes up some things, and if a field gets changed,
     * you need to track all the places you call it, and change that
     * therefore I used the variable-variable just to make it more flexible
     */
    public const NAME = 'name';

    protected $table = 'market_categories';
    protected $fillable = ['name'];

    public function companies()
    {
        return $this->hasMany(Company::class, Company::MARKET_CATEGORY_FOREIGN_KEY, 'id');
    }
}

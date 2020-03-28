<?php

namespace App\MarketCategory\Models;

use App\Company\Models\Company;
use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    public const NAME = 'name';

    protected $table = 'market_categories';
    protected $fillable = ['name'];

    public function companies()
    {
        return $this->hasMany(Company::class, 'market_category_id', 'id');
    }
}

<?php

namespace App\Company\Models;

use App\MarketCategory\Models\MarketCategory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public const MARKET_CATEGORY_FOREIGN_KEY = 'market_category_id';

    public const NAME = 'name';
    public const ROUND_LOT_SIZE = 'round_lot_size';
    public const SECURITY_NAME = 'security_name';
    public const SYMBOL = 'symbol';
    public const TEST_ISSUE = 'test_issue';
    public const FINANCIAL_STATUS = 'financial_status';


    protected $table = 'companies';
    protected $fillable = [
        self::NAME, self::MARKET_CATEGORY_FOREIGN_KEY, self::ROUND_LOT_SIZE,
        self::SECURITY_NAME, self::SYMBOL, self::TEST_ISSUE, self::FINANCIAL_STATUS
    ];

    public function marketCategory()
    {
        return $this->belongsTo(MarketCategory::class, self::MARKET_CATEGORY_FOREIGN_KEY);
    }
}

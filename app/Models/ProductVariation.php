<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariation extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['sell_price_discount', 'tax_amount'];

    public function getSellPriceDiscountAttribute()
    {
        return number_format(($this->sell_price * $this->product->discount) / 100, 2);
    }

    public function getTaxAmountAttribute()
    {
        return (float) ($this->selling_price * $this->product->tax) / 100;
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    protected function casts()
    {
        return [
            'attributes' => 'json',
        ];
    }
}

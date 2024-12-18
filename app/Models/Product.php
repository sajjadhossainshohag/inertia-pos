<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['thumbnail_url', 'selling_price_discount', 'tax_amount'];

    public function getSellingPriceDiscountAttribute()
    {
        return number_format($this->selling_price - ($this->selling_price * $this->discount) / 100, 2);
    }

    public function getTaxAmountAttribute()
    {
        return (float) ($this->selling_price * $this->tax) / 100;
    }

    public function getThumbnailUrlAttribute()
    {
        return Storage::url($this->thumbnail);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }
}

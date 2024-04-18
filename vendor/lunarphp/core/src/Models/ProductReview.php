<?php

namespace Lunar\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\BaseModel;

class ProductReview extends BaseModel
{
    protected $fillable = ['review', 'star_rating', 'product_id', 'product_variant_id', 'customer_id'];

    /**
     * Return the product relationship.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    /**
     * Return the product variant relationship.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Return the customer relationship.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $table_id
 * @property int $quantity
 * @property Product $product
 * @property Table $table
 */
class ProductTable extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'products_table';
    protected $fillable = ['product_id', 'table_id', 'quantity'];

    /**
     * @return BelongsTo
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

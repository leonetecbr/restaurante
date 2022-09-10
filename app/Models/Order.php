<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $value
 * @property int $table_id
 * @property array $products
 * @property string $time
 */
class Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $casts = [
        'products' => 'array'
    ];

    /**
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * @return Attribute
     */
    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (new DateTime($value))->format('d/m/Y H:i:s'),
        );
    }
}

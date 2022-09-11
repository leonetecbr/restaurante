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

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $casts = [
        'products' => 'array'
    ];

    /**
     * Converte o valor, de float para uma string no formato utilizado para Real brasileiro
     *
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Converte a data para o formato brasileiro
     *
     * @return Attribute
     */
    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (new DateTime($value))->format('d/m/Y H:i:s'),
        );
    }
}

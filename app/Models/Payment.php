<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $value
 * @property int $id
 * @property int $table_id
 */
class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @return Attribute
     */
    protected function method(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
        );
    }

    /**
     * @return Attribute
     */
    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (new DateTime($value))->format('d/m/Y H:i:s'),
        );
    }

    /**
     * @return string]
     */
    public function getColor(): string{
        $colors = ['primary', 'success', 'danger', 'warning', 'info', 'light'];
        $key = $this->table_id;
        while ($key > 5){
            $key -= 6;
        }
        $color = $colors[$key];
        return 'table-' . $color;
    }

    /**
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ '.number_format($this->value, 2, ',', '.');
    }
}

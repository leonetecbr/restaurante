<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $value
 */
class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function method(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value),
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (new DateTime($value))->format('d/m/Y H:i:s'),
        );
    }

    /**
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ '.number_format($this->value, 2, ',', '.');
    }
}

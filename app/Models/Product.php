<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $value
 * @property string $name
 */
class Product extends Model
{
    use HasFactory;

    /**
     * @return void
     */
    public static function initialize(): void
    {
        $time = date('Y-m-d H:i:s');
        self::insert([
            [
                'name' => 'água',
                'value' => 1.50,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'cerveja',
                'value' => 5.50,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'refrigerante',
                'value' => 7.10,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'PF',
                'value' => 18,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'brigadeiro',
                'value' => 2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);
    }

    /**
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
        );
    }

    /**
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }
}

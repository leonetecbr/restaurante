<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $value
 */
class Product extends Model
{
    use HasFactory;

    /**
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ '.number_format($this->value, 2, ',', '.');
    }

    /**
     * @return void
     */
    public static function initialize(): void
    {
        $time = date('Y-m-d H:i:s');
        self::insert([
            [
                'name' => 'Água',
                'value' => 1.50,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'Cerveja',
                'value' => 5.50,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'Refrigerante',
                'value' => 7.10,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'PF',
                'value' => 18,
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'name' => 'Brigadeiro',
                'value' => 2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);
    }
}

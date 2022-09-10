<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $capacity
 * @property bool $busy
 * @property array $products
 * @property string $created_at
 * @property string $updated_at
 */
class Table extends Model
{
    use HasFactory;

    protected $casts = [
        'products' => 'array',
        'busy' => 'boolean',
    ];

    public static function initialize(): void
    {
        $time = date('Y-m-d H:i:s');
        self::insert([
            [
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);
    }

    public function getBusyStatus(): string
    {
        return ($this->busy) ?
            '<i class="bi bi-circle-fill text-danger" data-bs-toggle="tooltip" data-bs-placement="right" title="Ocupada"></i>' :
            '<i class="bi bi-circle-fill text-success" data-bs-toggle="tooltip" data-bs-placement="right" title="Livre"></i>';
    }
}

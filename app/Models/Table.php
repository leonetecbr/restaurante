<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $casts = [
        'products' => 'array'
    ];

    public static function initialize(): void
    {
        $time = date('Y-m-d H:i:s');
        self::insert([
            [
                'created_at' => $time,
                'updated_at' => $time,
            ],[
                'created_at' => $time,
                'updated_at' => $time,
            ],[
                'created_at' => $time,
                'updated_at' => $time,
            ],[
                'created_at' => $time,
                'updated_at' => $time,
            ],[
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);
    }
}

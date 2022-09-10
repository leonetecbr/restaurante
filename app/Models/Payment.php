<?php

namespace App\Models;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $value
 * @property int $client
 * @property string $method
 * @property int $table_id
 * @property string $time
 */
class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @return string
     * @throws Exception
     */
    public function getTime(): string
    {
        return (new DateTime($this->time))->format('d/m/Y H:i:s');
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        $colors = ['primary', 'success', 'danger', 'warning', 'info', 'light'];
        $key = $this->table_id;
        while ($key > 5) {
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
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * @return Attribute
     */
    protected function method(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst($value),
        );
    }
}

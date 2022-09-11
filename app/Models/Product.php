<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $value
 * @property string $created_at
 * @property string $updated_at
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Adiciona os valores que já devem estar no banco de dados
     *
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
     * Retorna o nome do produto com a primeira letra minuscula caso não seja uma sigla
     *
     * @return string
     */
    public function getNameLower(): string
    {
        if (strtoupper($this->name) == $this->name){
            return $this->name;
        } elseif (mb_strlen($this->name) <= 1){
            return mb_strtolower($this->name);
        }

        return mb_strtolower(mb_substr($this->name, 0, 1)) . mb_substr($this->name, 1, mb_strlen($this->name));
    }

    /**
     *  Retorna o nome do produto com a primeira letra maiúscula caso não seja uma sigla
     *
     * @return string
     */
    public function getNameUpper(): string
    {
        if (strtoupper($this->name) == $this->name){
            return $this->name;
        } elseif (mb_strlen($this->name) <= 1){
            return mb_strtoupper($this->name);
        }

        return mb_strtoupper(mb_substr($this->name, 0, 1)) . mb_substr($this->name, 1, mb_strlen($this->name));
    }

    /**
     * Converte o valor, de float para uma string no formato utilizado para Real brasileiro
     *
     * @return string
     */
    public function getCurrentValue(): string
    {
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }
}

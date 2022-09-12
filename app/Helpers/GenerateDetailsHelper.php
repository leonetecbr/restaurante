<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class GenerateDetailsHelper
{
    /**
     * Gera os detalhes e transforma em um JSON
     *
     * @param Table|Order $item
     * @return array
     */
    public static function api(Table|Order $item): array
    {
        return self::getDetails($item->products, Product::all());
    }

    /**
     * Obtém os detalhes de uma mesa ou de um pedido
     *
     * @param array $products
     * @param Collection $data
     * @return array
     */
    public static function getDetails(array $products, Collection $data): array
    {
        $result = [];
        $sum = 0;

        $quantity = array_count_values($products);
        $products = array_unique($products);

        // Preenche o array com as informações de cada produto
        foreach ($products as $product) {
            // Procura as informações do produto
            foreach ($data as $info){
                if ($info->id === $product){
                    $match = $info;
                    break;
                }
            }

            $value = $match->value * $quantity[$product];
            $result[] = [
                'id' => $product,
                'quantity' => $quantity[$product],
                'name' => $match->getNameUpper(),
                'unitaryValue' => $match->getCurrentValue(),
                'value' => 'R$ ' . number_format($value, 2, ',', '.'),
            ];
            $sum += $value;
        }

        $result['sum'] = $sum;
        $result['total'] = 'R$ ' . number_format($sum, 2, ',', '.');

        return $result;
    }
}

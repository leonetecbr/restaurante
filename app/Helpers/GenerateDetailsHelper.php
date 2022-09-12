<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;

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
        $data = Product::all();
        $products = $item->products;

        return self::getDetails($products, $data);
    }

    /**
     * Obtém os detalhes de uma mesa ou de um pedido
     *
     * @param $products
     * @param $data
     * @return array
     */
    public static function getDetails($products, $data): array
    {
        $result = [];
        $sum = 0;

        $quantity = array_count_values($products);
        $products = array_unique($products);

        foreach ($products as $product) {
            foreach ($data as $info){
                if ($info->id == $product){
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

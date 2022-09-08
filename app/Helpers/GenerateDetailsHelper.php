<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;

class GenerateDetailsHelper
{
    /**
     * @param Table|Order $item
     * @return array
     */
    public static function api(Table|Order $item): array
    {
        $result = [];
        $sum = 0;
        $data = Product::all();
        $products = $item->products;
        $quantity = array_count_values($products);
        $products = array_unique($products);

        foreach ($products as $product){
            $match = $data[$product-1];
            $value = $match->value * $quantity[$product];
            $result[] = [
                'id' => $product,
                'quantity' => $quantity[$product],
                'name' => $match->name,
                'unitaryValue' => $match->getCurrentValue(),
                'value' => 'R$ '.number_format($value, 2, ',', '.'),
            ];
            $sum += $value;
        }

        $result['total'] = 'R$ '.number_format($sum, 2, ',', '.');
        return $result;
    }
}

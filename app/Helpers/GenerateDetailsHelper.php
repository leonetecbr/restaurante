<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class GenerateDetailsHelper
{
    /**
     * @param Table|Order $item
     * @return array
     */
    public static function get(Table|Order $item): array
    {
        return self::getDetails($item->products);
    }

    /**
     * Obtém os detalhes de uma mesa ou de um pedido
     *
     * @param Collection[] $products
     * @return array
     */
    public static function getDetails(Collection|array $products): array{
        $result = [];
        $sum = 0;

        foreach ($products as $product){
            $value = $product->product->value * $product->quantity;
            $result[] = [
                'id' => $product->product->id,
                'quantity' => $product->quantity,
                'name' => $product->product->getNameUpper(),
                'unitaryValue' => $product->product->getCurrentValue(),
                'value' => 'R$ ' . number_format($value, 2, ',', '.'),
            ];
            $sum += $value;
        }

        $result['sum'] = $sum;
        $result['total'] = 'R$ ' . number_format($sum, 2, ',', '.');

        return $result;
    }
}

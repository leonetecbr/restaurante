<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateDetailsHelper;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends Controller
{
    /**
     * Gera o histórico de pedidos
     *
     * @return View
     */
    #[Route('/admin/orders', name: 'admin.orders', methods: 'get')]
    public function get(): View
    {
        $orders = Order::withSum('products', 'quantity')->paginate();
        return view('admin.orders', ['orders' => $orders]);
    }

    /**
     * Gera os detalhes de um pedido
     *
     * @param Order $order
     * @return array
     */
    #[Route('/admin/orders/{order:id}/products', name: 'admin.orders.api', methods: 'get')]
    public function api(Order $order): array
    {
        return GenerateDetailsHelper::get($order);
    }

}

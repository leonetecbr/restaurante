<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Helpers\GenerateDetailsHelper;
use Illuminate\View\View;

class OrdersController extends Controller
{
    /**
     * @return View
     */
    public function get(): View
    {
        $orders = Order::paginate();
        return view('admin.orders', ['orders' => $orders]);
    }

    /**
     * @param Order $order
     * @return array
     */
    public function api(Order $order): array
    {
        return GenerateDetailsHelper::api($order);
    }

}

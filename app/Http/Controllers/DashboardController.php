<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Table;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
    /**
     * Gera a página inicial do administrador
     *
     * @return View
     * @throws Exception
     */
    #[Route('/admin', name: 'admin', methods: 'get')]
    public function admin(): View
    {
        $result = $this->countData();

        return view('admin.dashboard', $result);
    }

    /**
     * Conta os dados para alimentar o gráfico
     *
     * @return array
     * @throws Exception
     */
    private function countData(): array
    {
        $transactions = [];
        $sales = [];
        $values = [];
        $dates = [];

        for ($i = 6; $i >= 0; $i--){
            $data = Payment::whereDate('time', '=', date('Y-m-d', strtotime('-'. $i .' days')));

            $dates[] = date('d/m', strtotime('-'.$i.' days'));
            $transactions[] = $data->get()->count();
            $sales[] = $data->where('client', 1)->get()->count();
            $values[] = $data->get()->sum('value');
        }

        return [
            'dates' => $dates,
            'transactions' => $transactions,
            'sales' => $sales,
            'values' => $values,
        ];
    }

    /**
     * Gera a página inicial do garçom
     *
     * @return View
     */
    #[Route('/garcom', name: 'garcom', methods: 'get')]
    public function garcom(): View
    {
        $tables = Table::paginate(16);
        $products = Product::all();
        $quantityAdd = [];

        // Gera um array de produtos com quantidades vazias para ser usado pelo Javascript
        for ($i = 0; $i < count($products); $i++) {
            $quantityAdd[$products[$i]->id] = 0;
        }

        return view('garcom.dashboard', [
            'tables' => $tables,
            'products' => $products,
            'quantityAdd' => $quantityAdd
        ]);
    }
}

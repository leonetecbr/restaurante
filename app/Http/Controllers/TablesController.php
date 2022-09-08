<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TablesController extends Controller
{

    /**
     * @return View
     */
    public function get(): View
    {
        $tables = Table::paginate();
        return view('admin.tables', ['tables' => $tables]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function new(Request $request): RedirectResponse{
        $request->validate([
            'capacity' => 'required|integer|min:1',
        ]);

        $table = new Table;
        $table->capacity = $request->input('capacity');
        $table->save();

        return redirect()->back()->with('success', 'Mesa criada com sucesso!');
    }

    /**
     * @param Table $table
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit(Table $table, Request $request): RedirectResponse{
        $request->validate([
            'capacity' => 'required|integer|min:1',
        ]);

        $table->capacity = $request->input('capacity');
        $table->save();

        return redirect()->back()->with('success', 'Capacidade alterada com sucesso!');
    }

    /**
     * @param Table $table
     * @return RedirectResponse
     */
    public function delete(Table $table): RedirectResponse{
        $table->delete();

        return redirect()->back()->with('success', 'Mesa deletada com sucesso!');
    }

    /**
     * @param Table $table
     * @return array
     */
    public function api(Table $table): array
    {
        $result = [];
        $sum = 0;
        $data = Product::all();
        $products = $table->products;
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

    /**
     * @param Table $table
     * @param int $product
     * @return RedirectResponse
     */
    public function deleteProduct(Table $table, int $product): RedirectResponse
    {
        $table->products = array_diff($table->products, [$product]);
        $table->save();

        return redirect()->back()->with('success', 'Produto deletado com sucesso!');
    }
}

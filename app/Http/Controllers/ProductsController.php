<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends Controller
{
    /**
     * Gera a listagem de produtos
     *
     * @return View
     */
    #[Route('/admin/products', name: 'admin.products', methods: 'get')]
    public function get(): View
    {
        $products = Product::paginate();
        return view('admin.products', ['products' => $products]);
    }

    /**
     * Cria um produto
     *
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/admin/products/new', name: 'admin.products.new', methods: 'post')]
    public function new(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required|min:0',
        ]);

        $name = $request->input('name');

        if (strtoupper($name) !== $name) {
            $name = (mb_strlen($name) <= 1) ?
                        mb_strtolower($name) :
                        mb_strtolower(mb_substr($name, 0, 1)) . mb_substr($name, 1, mb_strlen($name));
        }

        $product = new Product;
        $product->name = $name;
        $product->value = $request->input('value');
        $product->save();

        return redirect()->back()->with('success', $product->getNameUpper() . ' criado com sucesso!');
    }

    /**
     * Edita o valor do produto
     *
     * @param Product $product
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/admin/products/edit/{product:id}', name: 'admin.products.edit', methods: 'post')]
    public function edit(Product $product, Request $request): RedirectResponse
    {
        $request->validate([
            'value' => 'required|min:0',
        ]);

        $product->value = $request->input('value');
        $product->save();

        return redirect()->back()->with('success', 'Valor do(a) ' . $product->getNameLower() . ' alterado com sucesso!');
    }


    /**
     * Deleta a mesa
     *
     * @param Product $product
     * @return RedirectResponse
     */
    #[Route('/admin/products/delete/{product:id}', name: 'admin.products.delete', methods: 'get')]
    public function delete(Product $product): RedirectResponse
    {
        $product->delete();

        $tables = Table::all();
        foreach ($tables as $table){
            if (in_array($product->id, $table->products)){
                $table->products = array_diff($table->products, [$product->id]);
                $table->save();
            }
        }

        $orders = Order::all();
        foreach ($orders as $order){
            if (in_array($product->id, $order->products)){
                $order->products = array_diff($order->products, [$product->id]);
                $order->save();
            }
        }

        return redirect()->back()->with('success', $product->getNameUpper() . ' deletado com sucesso!');
    }
}

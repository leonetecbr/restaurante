<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    /**
     * @return View
     */
    public function get(): View
    {
        $products = Product::paginate();
        return view('admin.products', ['products' => $products]);
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit(Product $product, Request $request): RedirectResponse
    {
        $request->validate([
            'value' => 'required|min:0',
        ]);

        $product->value = $request->input('value');
        $product->save();

        return redirect()->back()->with('success', 'Valor alterado com sucesso!');
    }
}

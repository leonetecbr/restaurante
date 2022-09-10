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

        if (strtoupper($product->name) == $product->name){
            $name = $product->name;
        } elseif (mb_strlen($product->name) <= 1){
            $name = mb_strtolower($product->name);
        } else{
            $name = mb_strtolower(mb_substr($product->name, 0, 1)) . mb_substr($product->name, 1, mb_strlen($product->name));
        }

        return redirect()->back()->with('success', 'Valor do(a) '.$name.' alterado com sucesso!');
    }
}

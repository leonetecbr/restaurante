<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        return redirect()->back()->with('success', 'Valor do(a) '.$product->getNameLower().' alterado com sucesso!');
    }
}

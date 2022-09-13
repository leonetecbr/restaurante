<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateDetailsHelper;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ProductTable;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\Routing\Annotation\Route;

class TablesController extends Controller
{

    /**
     * Gera a listagem de mesas
     *
     * @return View
     */
    #[Route('/admin/tables', name: 'admin.tables', methods: 'get')]
    public function get(): View
    {
        $tables = Table::withSum('products', 'quantity')->paginate();
        return view('admin.tables', ['tables' => $tables]);
    }

    /**
     * Cria uma mesa
     *
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/admin/tables/new', name: 'admin.tables.new', methods: 'post')]
    public function new(Request $request): RedirectResponse
    {
        $request->validate([
            'capacity' => 'required|integer|min:1',
        ]);

        $table = new Table;
        $table->capacity = $request->input('capacity');
        $table->save();

        return redirect()->back()->with('success', 'Mesa #' . $table->id . ' criada com sucesso!');
    }

    /**
     * Edita a capacidade da mesa
     *
     * @param Table $table
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/admin/tables/edit/{table:id}', name: 'admin.tables.edit', methods: 'post')]
    public function edit(Table $table, Request $request): RedirectResponse
    {
        $request->validate([
            'capacity' => 'required|integer|min:1',
        ]);

        $table->capacity = $request->input('capacity');
        $table->save();

        return redirect()->back()->with('success', 'Capacidade da mesa #' . $table->id . ' alterada com sucesso!');
    }

    /**
     * Deleta a mesa
     *
     * @param Table $table
     * @return RedirectResponse
     */
    #[Route('/admin/tables/delete/{table:id}', name: 'admin.tables.delete', methods: 'get')]
    public function delete(Table $table): RedirectResponse
    {
        $table->delete();

        return redirect()->back()->with('success', 'Mesa #' . $table->id . ' deletada com sucesso!');
    }

    /**
     * Gera os detalhes de uma mesa
     *
     * @param Table $table
     * @return array
     */
    #[Route('/admin/tables/{table:id}/products', name: 'admin.tables.api', methods: 'get')]
    public function api(Table $table): array
    {
        return GenerateDetailsHelper::get($table);
    }

    /**
     * Deleta um produto de uma mesa
     *
     * @param Table $table
     * @param int $product
     * @return RedirectResponse
     */
    #[Route('/admin/tables/delete/{table:id}/{product}', name: 'admin.tables.delete.product', methods: 'get')]
    public function deleteProduct(Table $table, int $product): RedirectResponse
    {
        ProductTable::where('table_id', $table->id)->where('product_id', $product)->delete();

        return redirect()->back()->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Altera o estado de uma mesa
     *
     * @param Table $table
     * @return RedirectResponse
     */
    #[Route('/garcom/tables/busy/{table:id}', name: 'garcom.tables.busy', methods: 'get')]
    public function busy(Table $table): RedirectResponse
    {
        $table->loadCount('products');

        if ($table->busy && $table->product_count > 0){
            return redirect()->back()
                            ->withErrors('Mesa com produtos não pode ser desocupada sem pagamento!');
        }

        $table->busy = !$table->busy;
        $table->save();

        $status = ($table->busy) ? 'ocupada' : 'desocupada';

        return redirect()->back()->with('success', 'Mesa #' . $table->id . ' ' . $status . ' com sucesso!');
    }

    /**
     * Adiciona produtos em uma mesa
     *
     * @param Table $table
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/garcom/tables/add/{table:id}', name: 'garcom.tables.add', methods: 'post')]
    public function add(Table $table, Request $request): RedirectResponse
    {
        $products = json_decode($request->input('products-add-json'), true);
        $prices = Product::all();
        $value = 0;

        foreach ($products as $key => $quantity) {
            // Calcula o valor dos produtos
            foreach ($prices as $price) {
                if ($price->id === $key) {
                    $value += $price->value * $quantity;
                    break;
                }
            }
        }

        $order = new Order;
        $order->value = $value;
        $order->table_id = $table->id;
        $order->save();

        foreach (array_keys($products) as $product) {
            $quantity = $products[$product];

            if ($quantity === 0) {
                continue;
            }

            $productOrder = new ProductOrder;
            $productOrder->product_id = $product;
            $productOrder->order_id = $order->id;
            $productOrder->quantity = $quantity;
            $productOrder->save();

            $productTable = ProductTable::firstOrNew([
                'table_id' => $table->id,
                'product_id' => $product,
            ]);

            if (empty($productTable->quantity)) {
                $productTable->quantity = $quantity;
            } else {
                $productTable->increment('quantity', $quantity);
            }

            $productTable->save();
        }

        return redirect()->back()->with('success', 'Produto(s) adicionado(s) com sucesso!');
    }

    /**
     * Registra o pagamento dos itens consumidos e desocupa a mesa
     *
     * @param Table $table
     * @param Request $request
     * @return View
     */
    #[Route('/garcom/tables/pay/{table:id}', name: 'garcom.tables.pay', methods: 'post')]
    public function pay(Table $table, Request $request): View
    {
        $products = $table->products;
        $price = 0;

        // Calcula o valor dos produto
        foreach ($products as $product) {
            $price += $product->product->value * $product->quantity;
        }

        $methods = $request->input('method');
        $value = $price / round(count($methods), 2);

        for ($i = 1; $i <= count($methods); $i++) {
            $pay = new Payment;
            $pay->value = $value;
            $pay->client = $i;
            $pay->method = $methods[$i - 1];
            $pay->table_id = $table->id;
            $pay->save();
        }

        $table->busy = false;
        $table->products()->delete();
        $table->save();

        return view('garcom.resume', [
            'tableId' => $table->id,
            'price' => 'R$ ' . number_format($price, 2, ',', '.'),
            'value' => 'R$ ' . number_format($value, 2, ',', '.'),
            'methods' => $methods,
            'details' => GenerateDetailsHelper::getDetails($products),
        ]);
    }
}

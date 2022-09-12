<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateDetailsHelper;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
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
        $tables = Table::paginate();
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
        return GenerateDetailsHelper::api($table);
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
        $table->products = array_diff($table->products, [$product]);
        $table->save();

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
        if ($table->busy && count($table->products) > 0){
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
        $add = [];
        $prices = Product::all()->toArray();
        $price = 0;

        foreach ($products as $key => $value) {
            // Transforma o array de [id => quantity] em [id, id, id]
            for ($i = 0; $i < $value; $i++) {
                $add[] = $key;
            }

            // Calcula o valor dos produtos
            if ($value !== 0) {
                for ($i = 0; $i < count($prices); $i++) {
                    if ($prices[$i]['id'] === $key) {
                        $price += $prices[$i]['value'] * $value;
                        break;
                    }
                }
            }
        }

        $table->products = array_merge($table->products, $add);
        $table->save();

        $order = new Order;
        $order->value = $price;
        $order->table_id = $table->id;
        $order->products = $add;
        $order->save();

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
        $prices = Product::all();
        $price = 0;

        // Calcula o valor dos produtos
        foreach ($products as $product) {
            for ($i = 0; $i < count($prices); $i++) {
                if ($prices[$i]->id === $product) {
                    $price += $prices[$i]->value;
                    break;
                }
            }
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
        $table->products = [];
        $table->save();

        return view('garcom.resume', [
            'tableId' => $table->id,
            'price' => 'R$ ' . number_format($price, 2, ',', '.'),
            'value' => 'R$ ' . number_format($value, 2, ',', '.'),
            'methods' => $methods,
            'details' => GenerateDetailsHelper::getDetails($products, $prices),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateDetailsHelper;
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
        return GenerateDetailsHelper::api($table);
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

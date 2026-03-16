<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index() {
        $stocks = Stock::all();
        return view('stocks.index', compact('stocks'));
    }

    public function create() {
        return view('stocks.create');
    }

    public function store(Request $request) {
        $request->validate([
            'item' => 'required|string|max:255',
            'categoria' => 'required|string', 
            'descricao' => 'required|string|max:255', 
            'quantidade' => 'required|integer|min:0'
        ]);

        Stock::create($request->all());
        return redirect()->route('stocks.index')->with('success', 'Item cadastrado com sucesso!');
    }

    public function edit(Stock $stock) {
        return view('stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock) {
        $request->validate([
            'item' => 'required|string|max:255',
            'categoria' => 'required|string', 
            'descricao' => 'required|string|max:255', 
            'quantidade' => 'required|integer|min:0'
        ]);

        $stock->update($request->all());
        return redirect()->route('stocks.index')->with('success', 'Estoque atualizado com sucesso!');
    }

    public function destroy(Stock $stock) {
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Item removido do estoque!');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index() {
        $stocks = \App\Models\Stock::all(); // Busca todos os estoques
        return view('stocks.index', compact('stocks'));
    }

    public function create() {
        return view('stocks.create');
    }

    public function store(Request $request) {
        $request -> validate([
            'item' => 'required|string|max:255',
            'categoria' => 'required|string', 
            'descricao' => 'required|string|max:255', 
            'quantidade' => 'required|integer|min:0'
        ]);

        \App\Models\Stock::create($request->all());

        return redirect()->route('stocks.index')->with('success', 'Item cadastrado com sucesso!');
    }
}

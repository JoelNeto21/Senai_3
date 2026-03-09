<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = \App\Models\Product::all(); // Busca todos os produtos
        return view('products.index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $request -> validate([
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0'
        ]);

        \App\Models\Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso!');
    }
}

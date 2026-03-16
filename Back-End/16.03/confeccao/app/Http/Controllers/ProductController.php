<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0'
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Product $product) {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product) {
        $request->validate([
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:0',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0'
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto removido do catálogo!');
    }
}
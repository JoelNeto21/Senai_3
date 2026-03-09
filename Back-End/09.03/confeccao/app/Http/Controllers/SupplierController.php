<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = \App\Models\Supplier::all(); // Busca todos os fornecedores
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('suppliers.create');
    }

    public function store(Request $request) {
        $request -> validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:20',
            'categoria' => 'required|string',
            'telefone' => 'required|string|max:25'
        ]);

        \App\Models\Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }
}

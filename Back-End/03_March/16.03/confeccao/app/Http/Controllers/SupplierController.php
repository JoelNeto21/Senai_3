<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('suppliers.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:20|unique:suppliers',
            'categoria' => 'required|string',
            'telefone' => 'required|string|max:25'
        ]);

        Supplier::create($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Supplier $supplier) {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier) {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:20|unique:suppliers,cnpj,' . $supplier->id,
            'categoria' => 'required|string',
            'telefone' => 'required|string|max:25'
        ]);

        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Fornecedor removido com sucesso!');
    }
}
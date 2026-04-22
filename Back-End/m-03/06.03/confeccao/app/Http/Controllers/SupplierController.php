<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = \App\Models\Supplier::all(); // Busca todos os fornecedores
        return view('suppliers.index', compact('suppliers'));
    }
}

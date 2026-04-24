<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = \App\Models\Order::all(); // Busca todos os pedidos
        return view('orders.index', compact('orders'));
    }

    public function create() {
        return view('orders.create');
    }

    public function store(Request $request) {
        $request -> validate([
            'data_pedido' => 'required|date',
            'data_entrega' => 'required|date|after_or_equal:data_pedido',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric'
        ]);

        \App\Models\Order::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Pedido cadastrado com sucesso!');
    }
}

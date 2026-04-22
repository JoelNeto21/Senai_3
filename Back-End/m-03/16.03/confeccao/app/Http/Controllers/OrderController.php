<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index() {
        // Ordena pelos mais recentes e pagina (opcional)
        $orders = Order::orderBy('data_entrega', 'asc')->get();
        return view('orders.index', compact('orders'));
    }

    public function create() {
        return view('orders.create');
    }

    public function store(Request $request) {
        $request->validate([
            'data_pedido' => 'required|date',
            'data_entrega' => 'required|date|after_or_equal:data_pedido',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'status' => 'required|string'
        ]);

        Order::create($request->all());
        return redirect()->route('orders.index')->with('success', 'Pedido registrado com sucesso!');
    }

    public function edit(Order $order) {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order) {
        $request->validate([
            'data_pedido' => 'required|date',
            'data_entrega' => 'required|date|after_or_equal:data_pedido',
            'quantidade' => 'required|integer|min:1',
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'status' => 'required|string'
        ]);

        $order->update($request->all());
        return redirect()->route('orders.index')->with('success', 'Pedido atualizado!');
    }

    public function destroy(Order $order) {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido removido!');
    }
}
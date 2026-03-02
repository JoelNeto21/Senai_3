<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = \App\Models\Order::all(); // Busca todos os pedidos
        return view('orders.index', compact('orders'));
    }
}

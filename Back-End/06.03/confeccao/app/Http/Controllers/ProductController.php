<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = \App\Models\Product::all(); // Busca todos os produtos
        return view('products.index', compact('products'));
    }
}

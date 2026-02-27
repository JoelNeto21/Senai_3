<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index() {
        $clients = \App\Models\Client::all(); // Busca todos os clients
        return view('clients.index', compact('clients'));
    }
}

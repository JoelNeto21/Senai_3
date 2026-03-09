<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index() {
        $clients = \App\Models\Client::all(); // Busca todos os clients
        return view('clients.index', compact('clients'));
    }

    public function create() {
        return view('clients.create');
    }

    public function store(Request $request) {
        $request -> validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:clients',
            'telefone' => 'required|string',
            'reserva' => 'required|integer|min:1'
        ]);

        \App\Models\Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index() {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create() {
        return view('clients.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|unique:clients',
            'telefone' => 'required|string',
            'reserva'  => 'required|integer|min:1'
        ]);

        Client::create($request->all());
        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Client $client) {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client) {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cpf'      => 'required|string|unique:clients,cpf,' . $client->id,
            'telefone' => 'required|string',
            'reserva'  => 'required|integer|min:1'
        ]);

        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client) {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente removido com sucesso!');
    }
}
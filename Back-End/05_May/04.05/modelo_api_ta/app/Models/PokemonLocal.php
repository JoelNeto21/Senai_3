<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonLocal extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'caminho_imagem'];
}

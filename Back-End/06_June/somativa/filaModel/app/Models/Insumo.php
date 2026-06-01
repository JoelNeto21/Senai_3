<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'unidade_medida',
        'preco_custo',
        'estoque',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'estoque' => 'decimal:2',
    ];
}

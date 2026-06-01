<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'categoria',
        'descricao',
        'valor_unitario',
        'quantidade',
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
        'quantidade' => 'integer',
    ];
}

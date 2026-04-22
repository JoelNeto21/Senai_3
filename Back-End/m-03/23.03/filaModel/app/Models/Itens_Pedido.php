<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itens_Pedido extends Model
{
    protected $guarded = [];

    protected $table = 'itens_pedidos';

    public function pedido(){
        return $this->belongsTo(Pedido::class);
    }

    public function produto(){
        return $this->belongsTo(Produto::class);
    }
}

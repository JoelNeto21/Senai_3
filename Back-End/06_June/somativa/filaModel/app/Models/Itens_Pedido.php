<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Itens_Pedido extends Model
{
    use HasFactory;

    protected $table = 'itens_pedidos';

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'preco_un',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco_un' => 'decimal:2',
    ];

    public function pedido(){
        return $this->belongsTo(Pedido::class);
    }

    public function produto(){
        return $this->belongsTo(Produto::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Itens_Pedido $item): void {
            if ($item->pedido) {
                $total = (float) $item->pedido->itens()->sum(DB::raw('quantidade * preco_un'));
                $item->pedido->update(['valor_total' => $total]);
            }
        });

        static::deleted(function (Itens_Pedido $item): void {
            if ($item->pedido) {
                $total = (float) $item->pedido->itens()->sum(DB::raw('quantidade * preco_un'));
                $item->pedido->update(['valor_total' => $total]);
            }
        });
    }
}

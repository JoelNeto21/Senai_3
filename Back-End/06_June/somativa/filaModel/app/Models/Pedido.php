<?php

namespace App\Models;

use App\Events\PedidoCriado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'status',
        'valor_total',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function itens(){
        return $this->hasMany(Itens_Pedido::class);
    }

    public function recalculateTotal(): void
    {
        $total = (float) $this->itens()->sum(DB::raw('quantidade * preco_un'));
        $this->update(['valor_total' => $total]);
    }

    protected static function booted(): void
    {
        static::created(function (Pedido $pedido): void {
            $pedido->loadMissing('itens.produto', 'cliente');
            event(new PedidoCriado($pedido));
        });

        static::updated(function (Pedido $pedido): void {
            $pedido->loadMissing('itens.produto', 'cliente');
            event(new PedidoCriado($pedido));
        });
    }
}

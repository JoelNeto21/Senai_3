<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'descricao',
        'quantidade',
        'movimentacao',
    ];

    protected $casts = [
        'quantidade' => 'integer',
    ];

    public function produto(){
        return $this->belongsTo(Produto::class);
    }

    protected static function booted(): void
    {
        // Validate stock before creating - throws ValidationException that Filament catches
        static::creating(function (MovimentacaoEstoque $movimentacao): void {
            $produto = $movimentacao->produto;

            if (! $produto) {
                return;
            }

            if (in_array($movimentacao->movimentacao, ['Saída', 'Saida'])) {
                $quantidade = (int) ($movimentacao->quantidade ?? 0);
                $estoqueDisponivel = (int) $produto->quantidade;

                if ($quantidade > $estoqueDisponivel) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantidade' => 'Quantidade solicitada maior que o estoque disponível (estoque atual: ' . $estoqueDisponivel . ').',
                    ]);
                }
            }
        });

        // Update product stock after successful creation
        static::created(function (MovimentacaoEstoque $movimentacao): void {
            $produto = $movimentacao->produto;

            if (! $produto) {
                return;
            }

            if ($movimentacao->movimentacao === 'Entrada') {
                $produto->quantidade += $movimentacao->quantidade;
            } else {
                $produto->quantidade -= $movimentacao->quantidade;
            }

            $produto->save();
        });
    }
}

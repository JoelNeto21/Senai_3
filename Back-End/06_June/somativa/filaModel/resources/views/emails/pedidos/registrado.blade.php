<x-mail::message>
# Olá, {{ $pedido->cliente->nome }}

Seu pedido foi registrado com sucesso.

## Resumo do Pedido

**Número do pedido:** #{{ $pedido->id }}  
**Data:** {{ $pedido->created_at->format('d/m/Y H:i') }}  
**Status:** {{ $pedido->status }}  
**Cliente:** {{ $pedido->cliente->nome }}

---

### Itens do Pedido

| Produto | Qtde | Valor Unitário | Subtotal |
|:---|:---:|---:|---:|
@foreach ($pedido->itens as $item)
| {{ $item->produto?->nome ?? 'Produto removido' }} | {{ $item->quantidade }} | R$ {{ number_format($item->preco_un, 2, ',', '.') }} | R$ {{ number_format($item->quantidade * $item->preco_un, 2, ',', '.') }} |
@endforeach

---

**Valor Total do Pedido:** **R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}**

---

Obrigado pela preferência.

Em caso de dúvidas entre em contato com nossa equipe.

Atenciosamente,  
**Equipe FilaModel**

<x-mail::button :url="url('/admin/pedidos/' . $pedido->id)">
Visualizar Pedido
</x-mail::button>
</x-mail::message>
# 🚀 GUIA RÁPIDO - EventPulse Somativa

## 📦 Arquivos Modificados

```
Aula10/somativa/
├── src/
│   ├── App.jsx          ✅ MODIFICADO - 5 features implementadas
│   ├── App.css          ✅ MODIFICADO - 3 alterações CSS + estilos novos
│   ├── index.css        (sem alterações)
│   ├── main.jsx         (sem alterações)
│   └── assets/
├── REVIEW.md            ✅ CRIADO - Documentação completa
└── GUIA_RAPIDO.md       ✅ CRIADO - Este arquivo
```

---

## ⚡ Como Usar as Features

### 1️⃣ Workshops no Topo
- Crie eventos tipo "Palestra", "Workshop" e "Painel"
- Workshops **sempre aparecem primeiro** automaticamente
- ✅ **Sem ação necessária do usuário**

### 2️⃣ Pesquisa em Tempo Real
```
1. Clique no campo de busca (🔍 Pesquisar evento por título...)
2. Comece a digitar qualquer letra do título do evento
3. A listagem filtra instantaneamente
4. Limpe para ver todos novamente
```

### 3️⃣ Sistema de Vagas
```
CRIAR EVENTO:
1. Escolha o tipo do evento (Palestra/Workshop/Painel)
2. Selecione número de vagas: 10, 30 ou 50
3. Clique "Agendar"

INSCREVER ALUNO:
1. Clique "Inscrever Aluno" no card do evento
2. Vagas diminuem em 1 a cada clique
3. Quando chegar a 0, botão vira "Esgotado" (cinza)
4. Não é mais possível inscrever
```

### 4️⃣ Limpar Cronograma
```
1. Clique no botão "🗑️ Limpar Cronograma" no topo (no header gradiente)
2. Uma caixa de diálogo aparece pedindo confirmação
3. Se clicar OK → Todos eventos são deletados (PERMANENTE)
4. Se clicar Cancelar → Nada acontece
```

### 5️⃣ Ver Estilizações
```
1. Clique no botão redondo ℹ️ no canto inferior direito
2. Um modal popup aparece
3. Leia sobre as 3 alterações CSS
4. Clique no ✕ ou fora do modal para fechar
```

---

## 🎨 Alterações Visuais que Pode Notar

| Elemento | Mudança |
|----------|---------|
| **Header** | Fundo branco → Gradiente azul-roxo brilhante |
| **Cards** | Sombra plana → Sombra 3D profunda |
| **Hover nos Cards** | Sem efeito → Cards "levantam" com mais sombra |
| **Cards Encerrados** | Normal → Tons de cinza (mais opaco) |
| **Cards Em Andamento** | Normal → Fundo amarelo claro |
| **Botão Inscrição** | N/A → Verde quando vagas disponíveis, cinza quando esgotado |

---

## 🧪 Checklist de Testes

Teste cada feature abaixo:

- [ ] **Feature 1** - Crie 2 Palestras e 1 Workshop. O Workshop aparece primeiro?
- [ ] **Feature 2** - Digite "React" na busca. Filtra apenas eventos com "React"?
- [ ] **Feature 3** - Crie evento com 10 vagas. Inscreva 10x. Botão fica esgotado?
- [ ] **Feature 4** - Clique "Limpar Cronograma". Aparece confirmação? Clique OK. Tudo desaparece?
- [ ] **Feature 5** - Clique no botão ℹ️ no canto inferior direito. Modal abre?

---

## 💾 Dados Persistentes

- ✅ Tudo é salvo automaticamente no **localStorage**
- ✅ Feche e abra o navegador → dados continuam
- ✅ Apenas "Limpar Cronograma" apaga os dados

---

## 🐛 Solução de Problemas

### "Não vejo as mudanças de CSS"
- Limpe cache: `Ctrl + F5` (Windows) ou `Cmd + Shift + R` (Mac)
- Ou: Abra DevTools (`F12`) → Aba Application → Limpar Storage

### "Eventos não aparecem na busca"
- Verifique se digitou corretamente (case-insensitive, mas precisa conter o texto)
- A busca é por **título** do evento, não por tipo

### "Não consegui inscrever no evento"
- Verifica se ainda há vagas (Vagas: X disponíveis)
- Se escrever "Esgotado", não há mais vagas

### "Botão 'Limpar Cronograma' não funciona"
- Verifique se clicou OK na confirmação (não Cancel)
- Se clicar Cancel, eventos continuam

---

## 📝 Estrutura de Dados de Evento

Cada evento no localStorage tem esta estrutura:

```javascript
{
  id: "uuid-único",
  title: "Nome do Evento",
  type: "Palestra" | "Workshop" | "Painel",
  status: "Agendado" | "Em Andamento" | "Encerrado",
  date: "DD/MM/YYYY",
  vagas: 10  // ← Novo! Número de vagas disponíveis
}
```

---

## 📱 Responsividade

- ✅ Cards se ajustam para telas menores
- ✅ Modal funciona em mobile
- ✅ Botão FAB (ℹ️) sempre visível
- ✅ Formulário adapta-se a diferentes tamanhos

---

## 🎯 Performance

- ✅ Filtros aplicam em tempo real (sem lag)
- ✅ Modal renderiza sob demanda
- ✅ Sem API externa = Rápido
- ✅ localStorage é local = Sem latência

---

## 📚 Referências de Código

### Todos os comentários no código seguem este padrão:
```javascript
// FEATURE X: Descrição do que faz
```

**Exemplo:**
```javascript
// FEATURE 2: Filtro de busca por título (case-insensitive)
return evt.title.toLowerCase().includes(searchTerm.toLowerCase());
```

Isso facilita procurar pela feature número no código! 🔍

---

## ✅ Status da Somativa

| Feature | Status | Documentação |
|---------|--------|--------------|
| 1. Workshops no Topo | ✅ Completo | [REVIEW.md](REVIEW.md) |
| 2. Filtro por Pesquisa | ✅ Completo | [REVIEW.md](REVIEW.md) |
| 3. Sistema de Vagas | ✅ Completo | [REVIEW.md](REVIEW.md) |
| 4. Limpar com Confirmação | ✅ Completo | [REVIEW.md](REVIEW.md) |
| 5. Estilização + Modal | ✅ Completo | [REVIEW.md](REVIEW.md) |
| Comentários Didáticos | ✅ Completo | Veja App.jsx |
| Arquivo Review.md | ✅ Criado | REVIEW.md |

---

## 🎓 O Que Você Aprendeu

Ao implementar esta somativa, você praticou:

✅ **React Hooks** - useState, useEffect  
✅ **Filtros e Ordenação** - filter(), sort()  
✅ **Condicionais em JSX** - ternários, operadores lógicos  
✅ **CSS Avançado** - Gradientes, sombras, animações, transições  
✅ **localStorage** - Persistência de dados  
✅ **Evento Nativo** - window.confirm()  
✅ **UI/UX** - Modal, botão FAB, efeitos hover  
✅ **Documentação** - Comentários didáticos e README

---

## 🚀 Próximos Passos (Sugestões)

Se quiser expandir ainda mais:

1. **Exportar para CSV** - Baixar eventos em Excel
2. **Filtro por Data** - Filtrar eventos por período
3. **Editar Evento** - Modificar evento após criação
4. **Múltiplas Salas** - Gerenciar eventos em diferentes salas
5. **Enviar Email** - Notificar alunos quando vagas acabam
6. **Gráficos** - Mostrar estatísticas de ocupação

---

**Desenvolvido com ❤️ como Somativa SENAI**

*Last updated: May 13, 2026*

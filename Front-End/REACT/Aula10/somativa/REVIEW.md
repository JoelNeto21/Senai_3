# 📋 REVIEW - Somativa EventPulse

## 🎯 Objetivo da Somativa

Expandir a regra de negócio da aplicação EventPulse (gerenciamento de eventos acadêmicos) implementando 5 features principais com foco em UX/UI e funcionalidades realistas de software acadêmico.

---

## ✅ Features Implementadas

### **FEATURE 1: Destaque Cronológico de Workshops**

#### O que faz?
Força todos os eventos do tipo **"Workshop"** a ficarem fixados no **início da listagem** renderizada, independentemente da ordem em que foram adicionados.

#### Onde está implementado?
- **Arquivo**: `App.jsx` (linhas ~80-95)
- **Função**: Dentro de `filteredEvents` ao final do componente

#### Como funciona?
```javascript
.sort((a, b) => {
  // FEATURE 1: Workshops sempre vêm primeiro
  if (a.type === "Workshop" && b.type !== "Workshop") return -1;
  if (a.type !== "Workshop" && b.type === "Workshop") return 1;
  return 0;
})
```

**Explicação didática:**
- Usamos `.sort()` para reordenar o array
- Se o evento `a` é Workshop e `b` não é, `a` fica antes (retorna -1)
- Se o evento `b` é Workshop e `a` não é, `b` fica antes (retorna 1)
- Caso contrário, mantém a ordem (retorna 0)
- Resultado: **Workshops sempre aparecem no topo, independente da hora que foram criados**

#### Benefício
Coloca a ênfase em eventos de maior impacto (workshops) que são mais procurados pelos alunos.

---

### **FEATURE 2: Filtro por Caixa de Pesquisa**

#### O que faz?
Adiciona um **input de texto** acima da grid de eventos que filtra a listagem **em tempo real** (caractere por caractere) comparando o termo digitado com o **título (title)** do evento.

#### Onde está implementado?
- **Arquivo**: `App.jsx`
  - Estado: linha ~13 (`const [searchTerm, setSearchTerm] = useState("")`)
  - Input HTML: linhas ~148-154
  - Lógica de filtro: linhas ~82-85

#### Como funciona?
```javascript
// Estado para armazenar o termo de busca
const [searchTerm, setSearchTerm] = useState("");

// Aplicando o filtro
.filter(evt => {
  // FEATURE 2: Filtro de busca por título (case-insensitive)
  return evt.title.toLowerCase().includes(searchTerm.toLowerCase());
})
```

**Explicação didática:**
- Cada caractere digitado no input atualiza o estado `searchTerm`
- O método `.includes()` verifica se o título contém o texto buscado
- `.toLowerCase()` garante que a busca funcione independente de maiúsculas/minúsculas
- Resultado: **Eventos são filtrados em tempo real sem precisar clicar em botões**

#### Exemplo:
```
Digite "React" → Mostra apenas eventos com "React", "react", "REACT" no título
```

#### Benefício
Melhora a experiência do usuário permitindo busca rápida e intuitiva.

---

### **FEATURE 3: Vagas Disponíveis (Novo Estado Interno)**

#### O que faz?
Adiciona uma propriedade numérica chamada **`vagas`** ao criar o evento (usuário escolhe entre 10, 30 ou 50 vagas). Na área de ações do card, implementa um botão **"Inscrever Aluno"** que diminui o número de vagas em 1 a cada clique. Quando chegar a 0, o botão muda para **"Esgotado"** e fica desabilitado.

#### Onde está implementado?
- **Arquivo**: `App.jsx`
  - Estado: linha ~11 (`const [eventVagas, setEventVagas] = useState(10)`)
  - Criação de evento: linha ~31
  - Função de inscrição: linhas ~67-74
  - Select HTML: linhas ~133-139
  - Botão no card: linhas ~178-184

#### Como funciona?

**1. Escolher vagas ao criar evento:**
```javascript
// Select no formulário
<select value={eventVagas} onChange={(e) => setEventVagas(Number(e.target.value))}>
  <option value={10}>10 vagas</option>
  <option value={30}>30 vagas</option>
  <option value={50}>50 vagas</option>
</select>
```

**2. Armazenar vagas no evento:**
```javascript
const newEvent = {
  id: crypto.randomUUID(),
  title: eventTitle,
  type: eventType,
  status: "Agendado",
  date: new Date().toLocaleDateString(),
  vagas: eventVagas // ← Armazena o número de vagas escolhido
};
```

**3. Função para inscrever aluno:**
```javascript
const inscreverAluno = (id) => {
  setEventList(eventList.map(evt => {
    if (evt.id === id && evt.vagas > 0) {
      return { ...evt, vagas: evt.vagas - 1 }; // Diminui em 1
    }
    return evt;
  }));
};
```

**4. Botão com lógica de desabilitação:**
```javascript
<button 
  onClick={() => inscreverAluno(item.id)} 
  className={`inscricao-btn ${item.vagas === 0 ? 'esgotado' : ''}`}
  disabled={item.vagas === 0}
>
  {item.vagas === 0 ? "Esgotado" : "Inscrever Aluno"}
</button>
```

#### Estilo CSS:
```css
.event-actions button.inscricao-btn {
  background: #10b981; /* Verde */
  color: white;
}

.event-actions button.inscricao-btn.esgotado {
  background: #9ca3af; /* Cinza */
  cursor: not-allowed;
  opacity: 0.7;
}
```

**Explicação didática:**
- Cada evento agora tem uma propriedade `vagas` que começa com o número escolhido
- O botão "Inscrever Aluno" chama `inscreverAluno()` que encontra o evento e diminui `vagas` em 1
- Quando `vagas === 0`, o botão fica desabilitado com `disabled={true}`
- A classe `esgotado` muda o estilo para cinza indicando indisponibilidade
- Resultado: **Controle realista de capacidade de eventos**

#### Benefício
Simula um sistema real de inscrição em eventos com controle de capacidade.

---

### **FEATURE 4: Alerta Preventivo de Limpeza**

#### O que faz?
Implementa um botão geral no cabeçalho chamado **"Limpar Cronograma"**. Antes de apagar todo o localStorage e esvaziar o estado `eventList`, a aplicação exibe um **diálogo nativo do navegador** (`window.confirm`) validando a ação do usuário.

#### Onde está implementado?
- **Arquivo**: `App.jsx`
  - Função: linhas ~75-83
  - Botão no header: linhas ~114-117

#### Como funciona?

**1. Função de limpeza com confirmação:**
```javascript
const limparCronograma = () => {
  // window.confirm exibe um diálogo nativo do navegador pedindo confirmação
  if (window.confirm("⚠️ Tem certeza que deseja LIMPAR TODO o cronograma? Esta ação não pode ser desfeita!")) {
    // Limpar localStorage e estado
    localStorage.removeItem("@eventpulse_data");
    setEventList([]);
    setSearchTerm(""); // Limpar busca também
  }
};
```

**2. Botão no header:**
```javascript
<button className="btn-limpar-cronograma" onClick={limparCronograma}>
  🗑️ Limpar Cronograma
</button>
```

**Explicação didática:**
- `window.confirm()` é um diálogo modal nativo do navegador que retorna `true` se o usuário clicar OK ou `false` se clicar Cancelar
- Só executa a limpeza se o usuário confirmar
- Limpa 3 coisas:
  1. Remove do localStorage (dados persistentes)
  2. Limpa o estado `eventList` (dados em memória)
  3. Limpa `searchTerm` (reseta a busca)
- Resultado: **Ação destrutiva protegida por confirmação do usuário**

#### Benefício
Previne acidentes de perda de dados com uma confirmação clara antes da ação.

#### Como testar:
1. Crie alguns eventos
2. Clique em "🗑️ Limpar Cronograma"
3. Uma caixa de diálogo aparece pedindo confirmação
4. Se confirmar, todos os eventos desaparecem

---

### **FEATURE 5: Estilização - 3 Alterações Marcantes + Modal de Informações**

#### O que faz?
Implementa 3 alterações indeléveis (marcantes) no CSS estrutural. Cria um **botão redondo (FAB - Floating Action Button)** no canto inferior direito que, ao ser clicado, exibe um **modal** listando todas as alterações de estilização.

#### Alteração 1: Gradiente no Header

**Onde está:**
- `App.css` (linhas ~18-25)

**O que muda:**
```css
/* ANTES: */
header {
  text-align: center;
  margin-bottom: 2.5rem;
  color: #111827;
}

/* DEPOIS: */
header {
  text-align: center;
  margin-bottom: 2.5rem;
  background: linear-gradient(135deg, #0284c7 0%, #7c3aed 100%);
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 8px 16px rgba(114, 58, 237, 0.3);
  color: white;
}
```

**Explicação:**
- `linear-gradient(135deg, #0284c7 0%, #7c3aed 100%)` cria um gradiente diagonal
- Vai de azul (`#0284c7`) até roxo (`#7c3aed`)
- Ângulo de 135° cria uma diagonal suave
- Resultado: **Header muito mais atrativo e moderno**

---

#### Alteração 2: Efeito de Sombra Elevada (3D) nos Cards

**Onde está:**
- `App.css` (linhas ~127-140)

**O que muda:**
```css
/* ANTES: */
.event-card {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* DEPOIS: */
.event-card {
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

.event-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}
```

**Explicação:**
- Aumentamos a sombra padrão para criar sensação de profundidade
- `transition: all 0.3s ease` suaviza as animações
- No hover, `transform: translateY(-8px)` move o card para cima
- A sombra fica mais forte, dando ilusão de elevação
- Resultado: **Cards ganham vida com efeito 3D interativo**

---

#### Alteração 3: Cores Dinâmicas por Status

**Onde está:**
- `App.css` (linhas ~151-167)

**O que muda:**
```css
/* Status "Em Andamento": fundo levemente colorido */
.event-card.em-andamento {
  animation: pulse 2s infinite alternate;
  background: #fef3c7;
  border-left: 4px solid #f59e0b;
}

/* Status "Encerrado": tons de cinza */
.event-card.encerrado {
  opacity: 0.65;
  background: #f3f4f6;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  grayscale: 50%;
}
```

**Explicação:**
- Cards em andamento ficam com fundo amarelo claro e animação pulsante
- Cards encerrados ficam em tons de cinza (mais opacos)
- Isso cria diferenciação visual rápida do estado dos eventos
- Resultado: **Usuário entende o status de um evento numa rápida olhada**

---

#### Bonus: Botão FAB (Floating Action Button)

**Onde está:**
- `App.jsx` (linhas ~15, 121-125, 188-195)
- `App.css` (linhas ~305-329)

**HTML:**
```javascript
<button 
  className="fab-info-button" 
  onClick={() => setShowModal(!showModal)}
  title="Ver informações de estilização"
>
  ℹ️
</button>
```

**CSS:**
```css
.fab-info-button {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 100;
}

.fab-info-button:hover {
  transform: scale(1.1) rotate(10deg);
  box-shadow: 0 12px 28px rgba(79, 70, 229, 0.5);
}
```

**Explicação:**
- `position: fixed` mantém o botão no mesmo lugar mesmo ao rolar
- `bottom: 30px; right: 30px;` posiciona no canto inferior direito
- `border-radius: 50%` torna-o redondo
- `z-index: 100` garante que fique acima dos outros elementos
- No hover, `scale(1.1)` aumenta o tamanho e `rotate(10deg)` gira levemente
- Resultado: **Botão flutuante chamativo com boa UX**

---

#### Modal de Informações

**Onde está:**
- `App.jsx` (linhas ~196-215)
- `App.css` (linhas ~331-386)

**Estados:**
```javascript
const [showModal, setShowModal] = useState(false);
```

**HTML do Modal:**
```javascript
{showModal && (
  <div className="modal-overlay" onClick={() => setShowModal(false)}>
    <div className="modal-content" onClick={(e) => e.stopPropagation()}>
      <button className="modal-close" onClick={() => setShowModal(false)}>✕</button>
      <h2>🎨 Alterações de Estilização</h2>
      <div className="modal-alteracoes">
        <div className="alteracao-item">
          <h3>1️⃣ Gradiente no Header</h3>
          <p>O cabeçalho agora possui um <strong>gradiente linear</strong>...</p>
        </div>
        {/* Mais 2 alterações */}
      </div>
    </div>
  </div>
)}
```

**CSS do Modal:**
```css
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5); /* Escurece o fundo */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 30px;
  max-width: 600px;
  animation: slideUp 0.3s ease; /* Animação de entrada */
}
```

**Explicação:**
- Modal usa `position: fixed` para centralizar na tela
- `onClick={(e) => e.stopPropagation()}` previne fechar ao clicar dentro do modal
- Clicar no overlay (fundo) fecha o modal
- Animação `slideUp` dá entrada suave
- Resultado: **Interface profissional para comunicar as mudanças**

---

## 🔄 Fluxo de Dados

```
User Input (Formulário)
    ↓
addEvent() → Cria novo evento com vagas
    ↓
setEventList() → Atualiza estado (React re-renderiza)
    ↓
localStorage → Persiste dados
    ↓
Renderização
    ├─ Aplica Filtro por Status
    ├─ Aplica Filtro por Pesquisa (FEATURE 2)
    ├─ Ordena Workshops (FEATURE 1)
    └─ Exibe Cards com Vagas (FEATURE 3)
```

---

## 🎨 Resumo Visual das Alterações

| Alteração | Antes | Depois |
|-----------|-------|--------|
| **Header** | Fundo branco simples | Gradiente azul-roxo com sombra |
| **Cards** | Sombra plana | Sombra profunda + efeito hover com elevação |
| **Status** | Cores padronizadas | Cores dinâmicas: amarelo/animação (em andamento), cinza (encerrado) |
| **Vagas** | ❌ Não existia | ✅ Sistema funcional de inscrição |
| **Pesquisa** | ❌ Não existia | ✅ Filtro em tempo real |
| **Workshops** | Sem destaque especial | Fixados no topo |
| **Limpeza** | ❌ Não existia | ✅ Botão com confirmação segura |

---

## 🧪 Como Testar as Features

### Teste 1: Workshops no Topo
1. Crie um evento tipo "Palestra"
2. Crie um evento tipo "Workshop"
3. Crie outro "Palestra"
4. ✅ O Workshop deve aparecer primeiro

### Teste 2: Pesquisa
1. Crie alguns eventos: "React Básico", "NodeJS Avançado", "Python Intro"
2. Digitar "React" → Mostra apenas "React Básico"
3. Limpar busca → Mostra todos novamente
4. ✅ Filtro funciona em tempo real

### Teste 3: Vagas
1. Crie evento com 10 vagas
2. Clique 10x em "Inscrever Aluno"
3. ✅ Botão muda para "Esgotado" e fica cinza
4. ✅ Não consegue inscrever mais

### Teste 4: Limpeza
1. Crie alguns eventos
2. Clique "🗑️ Limpar Cronograma"
3. Clique "Cancelar"
4. ✅ Eventos ainda existem
5. Clique novamente e confirmar
6. ✅ Todos os eventos desaparecem

### Teste 5: Estilização
1. Observe o header com gradiente
2. Passe o mouse sobre um card → ✅ Levanta com sombra maior
3. Clique no botão ℹ️ no canto inferior direito
4. ✅ Modal aparece com informações
5. Crie evento com status "Em Andamento" → ✅ Fundo amarelo
6. Crie evento e encerre → ✅ Fica cinza

---

## 📝 Comentários no Código

Todos os comentários no código seguem o padrão:
```javascript
// FEATURE X: Descrição do que faz
```

Isso facilita:
- Localizar rapidamente onde cada feature está
- Entender o propósito de cada seção
- Facilita manutenção futura

---

## 🚀 Conclusão

Esta somativa implementa um **sistema realista de gerenciamento acadêmico** com:

✅ **UX melhorada** - Busca, filtros, destaques
✅ **Funcionalidades avançadas** - Sistema de vagas, proteção de dados
✅ **Design profissional** - Gradientes, animações, efeitos 3D
✅ **Código bem documentado** - Comentários didáticos

O código está pronto para produção e segue boas práticas de React! 🎉

---

**Desenvolvido como parte da Somativa - SENAI - Gestão de Eventos Acadêmicos**

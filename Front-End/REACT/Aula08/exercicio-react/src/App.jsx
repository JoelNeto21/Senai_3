import React, { useState, useEffect } from 'react';

// --- DEFINIÇÃO DOS COMPONENTES (USANDO FUNCTION) ---
export function Header({ titulo }) {
  // Componente apresentacional: recebe a prop 'titulo' e renderiza o cabeçalho.
  return (
    <header style={{
      background: '#646cff', padding: '20px', color: 'white',
      textAlign: 'center'
    }}>
      <h1>{titulo}</h1>
    </header>
  );
}
export function InputUsuario({ nome, setNome }) {
  // Input controlado: 'nome' é o valor atual e 'setNome' atualiza o state no componente pai.
  return (
    <div style={{ margin: '20px 0' }}>
      <label>Digite seu nome: </label>
      <input
        type="text"
        value={nome}
        onChange={(e) => setNome(e.target.value)}
        placeholder="Seu nome aqui..."
        style={{ padding: '8px', borderRadius: '4px', border: '1px solid #ccc' }}
      />
    </div>
  );
}
export function CardSaudacao({ nome, temaEscuro }) {
  // Renderização condicional por props: personaliza saudação e tema (claro/escuro).
  const estilo = {
    padding: '15px',
    borderRadius: '8px',
    backgroundColor: temaEscuro ? '#333' : '#f9f9f9',
    color: temaEscuro ? '#fff' : '#000',
    marginTop: '10px',
    border: '1px solid #ddd'
  };
  return (
    <div style={estilo}>
      <h3>Olá, {nome || 'Visitante'}!</h3>
      <p>Bem-vindo ao exercício de componentes com Vite e Functions.</p>
    </div>
  );
}
export function ContadorCliques({ cliques, setCliques }) {
  // Componente interativo: exibe e incrementa o contador via props de estado.
  return (
    <div style={{
      padding: '15px', border: '1px solid #646cff', marginTop:
        '10px', borderRadius: '8px'
    }}>
      <p>Botão clicado <strong>{cliques}</strong> vezes</p>
      <button onClick={function () { setCliques(cliques + 1) }}>
        Incrementar
      </button>
    </div>
  );
}
export function ThemeToggle({ dark, setDark }) {
  // Toggle de tema: alterna booleano no estado do componente pai.
  return (
    <button onClick={function () { setDark(!dark) }} style={{
      marginTop: '10px'
    }}>
      Mudar para modo {dark ? 'Claro' : 'Escuro'}
    </button>
  );
}
export function ListaRecursos({ itens }) {
  // Lista dinâmica: usa map para transformar array de props em itens de interface.
  return (
    <ul style={{ textAlign: 'left', display: 'inline-block' }}>
      {itens.map(function (item, index) {
        return <li key={index} style={{ marginBottom: '5px' }}>{item}</li>;
      })}
    </ul>
  );
}
// --- COMPONENTE PRINCIPAL ---
export default function App() {
  // STATES PRINCIPAIS DA APLICAÇÃO
  // 'nome': armazena o texto digitado no input.
  const [nome, setNome] = useState('');
  // 'cliques': controla quantas vezes o botão foi pressionado.
  const [cliques, setCliques] = useState(0);
  // 'temaEscuro': define o tema global da página (false=claro, true=escuro).
  const [temaEscuro, setTemaEscuro] = useState(false);

  // Dados estáticos enviados por props para o componente de lista.
  const recursosReact = ['Vite', 'Function Components', 'Named Exports',
    'useState', 'useEffect', 'Props'];

  // HOOK useEffect: efeito colateral que sincroniza o título da aba com 'cliques'.
  // Executa novamente apenas quando 'cliques' muda (array de dependências).
  useEffect(function () {
    document.title = "Cliques: " + cliques;
  }, [cliques]);

  // Estilo base do container muda dinamicamente com o estado de tema.
  const containerStyle = {
    fontFamily: 'Inter, system-ui, Arial, sans-serif',
    textAlign: 'center',
    minHeight: '100vh',
    backgroundColor: temaEscuro ? '#242424' : '#ffffff',
    color: temaEscuro ? '#ffffff' : '#213547',
    transition: '0.25s'
  };
  return (
    <div style={containerStyle}>
      {/* Props: envia o texto de título para o Header */}
      <Header titulo="Exercício React com Functions" />

      <main style={{ padding: '20px', maxWidth: '800px', margin: '0 auto' }}>
        {/* Props de state lifting: filho lê 'nome' e solicita atualização via 'setNome' */}
        <InputUsuario nome={nome} setNome={setNome} />

        {/* Props para personalização de conteúdo e tema visual */}
        <CardSaudacao nome={nome} temaEscuro={temaEscuro} />

        <div style={{
          display: 'flex', justifyContent: 'center', gap: '20px',
          alignItems: 'center', flexWrap: 'wrap'
        }}>
          {/* Props de contador: valor atual + função para incrementar */}
          <ContadorCliques cliques={cliques} setCliques={setCliques} />
          {/* Props de tema: valor atual + função para alternar */}
          <ThemeToggle dark={temaEscuro} setDark={setTemaEscuro} />
        </div>
        <div style={{ marginTop: '30px' }}>
          <h4>Conceitos chave identificados:</h4>
          {/* Props com array de dados para renderização da lista */}
          <ListaRecursos itens={recursosReact} />
        </div>
      </main>
    </div>
  );
}
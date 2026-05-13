import React, { useState, useEffect } from 'react';
import { FiTrash2, FiSearch, FiInbox, FiInfo, FiX, FiPalette } from 'react-icons/fi';
import './App.css';

function App() {
  const [eventTitle, setEventTitle] = useState("");
  const [eventType, setEventType] = useState("Palestra");
  // FEATURE 3: Adicionamos estado para controlar o número de vagas selecionadas
  const [eventVagas, setEventVagas] = useState(10);
  const [eventList, setEventList] = useState([]);
  const [filter, setFilter] = useState("Todos");
  // FEATURE 2: Estado para armazenar o termo de busca digitado
  const [searchTerm, setSearchTerm] = useState("");
  // FEATURE 5: Estados para controlar o modal com informações de estilização
  const [showModal, setShowModal] = useState(false);

  // Carregar dados iniciais do LocalStorage
  useEffect(() => {
    const savedEvents = localStorage.getItem("@eventpulse_data");
    if (savedEvents) setEventList(JSON.parse(savedEvents));
  }, []);

  // Sincronizar alterações com o LocalStorage
  useEffect(() => {
    localStorage.setItem("@eventpulse_data", JSON.stringify(eventList));
  }, [eventList]);

  const addEvent = (e) => {
    e.preventDefault();
    if (!eventTitle.trim()) return;

    // FEATURE 3: Incluímos a propriedade 'vagas' no novo evento
    const newEvent = {
      id: crypto.randomUUID(),
      title: eventTitle,
      type: eventType,
      status: "Agendado", // Status inicial padrão
      date: new Date().toLocaleDateString(),
      vagas: eventVagas // Armazena o número de vagas escolhido pelo usuário
    };

    setEventList([newEvent, ...eventList]);
    setEventTitle("");
    setEventVagas(10); // Reset para valor padrão
  };

  const toggleStatus = (id) => {
    setEventList(eventList.map(evt => {
      if (evt.id === id) {
        // Rotaciona o status do evento sequencialmente
        const nextStatus = evt.status === "Agendado" ? "Em Andamento" :
          evt.status === "Em Andamento" ? "Encerrado" : "Agendado";
        return { ...evt, status: nextStatus };
      }
      return evt;
    }));
  };

  const deleteEvent = (id) => {
    setEventList(eventList.filter(evt => evt.id !== id));
  };

  // FEATURE 3: Função para inscrever um aluno (diminui vagas em 1)
  const inscreverAluno = (id) => {
    setEventList(eventList.map(evt => {
      if (evt.id === id && evt.vagas > 0) {
        return { ...evt, vagas: evt.vagas - 1 };
      }
      return evt;
    }));
  };

  // FEATURE 4: Função para limpar cronograma com confirmação
  const limparCronograma = () => {
    // window.confirm exibe um diálogo nativo do navegador pedindo confirmação
    if (window.confirm("Tem certeza que deseja LIMPAR TODO o cronograma? Esta ação não pode ser desfeita!")) {
      // Limpar localStorage e estado
      localStorage.removeItem("@eventpulse_data");
      setEventList([]);
      setSearchTerm(""); // Limpar busca também
    }
  };

  // FEATURE 1 + 2: Aplicar filtros e ordenação
  const filteredEvents = eventList
    .filter(evt => {
      // Filtro de status
      if (filter === "Agendados") return evt.status === "Agendado";
      if (filter === "Em Andamento") return evt.status === "Em Andamento";
      if (filter === "Encerrados") return evt.status === "Encerrado";
      return true;
    })
    .filter(evt => {
      // FEATURE 2: Filtro de busca por título (case-insensitive)
      return evt.title.toLowerCase().includes(searchTerm.toLowerCase());
    })
    .sort((a, b) => {
      // FEATURE 1: Workshops sempre vêm primeiro
      if (a.type === "Workshop" && b.type !== "Workshop") return -1;
      if (a.type !== "Workshop" && b.type === "Workshop") return 1;
      return 0;
    });

  return (
    <div className="app-container">
      <header>
        <h1>EventPulse</h1>
        <p>Gestão de Eventos Acadêmicos</p>
        {/* FEATURE 4: Botão "Limpar Cronograma" no cabeçalho */}
        <button className="btn-limpar-cronograma" onClick={limparCronograma}>
          <FiTrash2 aria-hidden="true" /> Limpar Cronograma
        </button>
      </header>

      <section className="form-section">
        <form onSubmit={addEvent}>
          <input
            value={eventTitle}
            onChange={(e) => setEventTitle(e.target.value)}
            placeholder="Nome do evento ou atividade..."
          />
          <select value={eventType} onChange={(e) => setEventType(e.target.value)}>
            <option value="Palestra">Palestra</option>
            <option value="Workshop">Workshop</option>
            <option value="Painel">Painel</option>
          </select>
          {/* FEATURE 3: Select para escolher número de vagas */}
          <select value={eventVagas} onChange={(e) => setEventVagas(Number(e.target.value))}>
            <option value={10}>10 vagas</option>
            <option value={30}>30 vagas</option>
            <option value={50}>50 vagas</option>
          </select>
          <button type="submit">Agendar</button>
        </form>
      </section>

      {/* FEATURE 2: Input de pesquisa para filtrar eventos por título */}
      <section className="search-section">
        <input
          type="text"
          className="search-input"
          placeholder="Pesquisar evento por título..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
        <FiSearch className="search-icon" aria-hidden="true" />
      </section>

      <section className="filter-section">
        {["Todos", "Agendados", "Em Andamento", "Encerrados"].map(f => (
          <button
            key={f}
            className={filter === f ? "active" : ""}
            onClick={() => setFilter(f)}
          >
            {f}
          </button>
        ))}
      </section>

      <main className="event-grid">
        {filteredEvents.length === 0 ? (
          <p className="no-events"><FiInbox aria-hidden="true" /> Nenhum evento encontrado</p>
        ) : (
          filteredEvents.map(item => (
            <div
              key={item.id}
              className={`event-card ${item.type.toLowerCase()}
${item.status.toLowerCase().replace(" ", "-")}`}
            >
              <div className="event-content">
                <h3>{item.title}</h3>
                <span className="event-tag">Tipo: {item.type}</span>
                <span className="status-badge">Status: {item.status}</span>
                {/* FEATURE 3: Exibir vagas disponíveis */}
                <span className="vagas-info">Vagas: {item.vagas > 0 ? `${item.vagas} disponíveis` : 'Esgotado'}</span>
                <small>Registrado em: {item.date}</small>
              </div>
              <div className="event-actions">
                <button onClick={() => toggleStatus(item.id)} className="status-btn">
                  {item.status === "Agendado" ? "Iniciar" : item.status === "Em Andamento"
                    ? "Encerrar" : "Reiniciar"}
                </button>
                {/* FEATURE 3: Botão "Inscrever Aluno" que diminui vagas ou fica desabilitado */}
                <button 
                  onClick={() => inscreverAluno(item.id)} 
                  className={`inscricao-btn ${item.vagas === 0 ? 'esgotado' : ''}`}
                  disabled={item.vagas === 0}
                >
                  {item.vagas === 0 ? "Esgotado" : "Inscrever Aluno"}
                </button>
                <button onClick={() => deleteEvent(item.id)} className="delete">
                  Remover
                </button>
              </div>
            </div>
          ))
        )}
      </main>

      {/* FEATURE 5: Botão redondo no canto inferior direito com modal */}
      <button 
        className="fab-info-button" 
        onClick={() => setShowModal(!showModal)}
        title="Ver informações de estilização"
      >
        <FiInfo aria-hidden="true" />
      </button>

      {/* FEATURE 5: Modal com informações sobre estilizações */}
      {showModal && (
        <div className="modal-overlay" onClick={() => setShowModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <button className="modal-close" onClick={() => setShowModal(false)}><FiX aria-hidden="true" /></button>
            <h2><FiPalette aria-hidden="true" /> Alterações de Estilização</h2>
            <div className="modal-alteracoes">
              <div className="alteracao-item">
                <h3>1. Gradiente no Header</h3>
                <p>O cabeçalho agora possui um <strong>gradiente linear</strong> que vai de azul para roxo, criando uma aparência mais moderna e atraente.</p>
              </div>
              <div className="alteracao-item">
                <h3>2. Cards com Efeito de Sombra Elevada</h3>
                <p>Os cards dos eventos agora possuem um <strong>efeito de sombra em 3D</strong> que aumenta quando você passa o mouse sobre eles, dando a sensação de elevação.</p>
              </div>
              <div className="alteracao-item">
                <h3>3. Cores Dinâmicas por Status</h3>
                <p>Cards com status "Encerrado" agora aparecem em <strong>tons de cinza</strong> e os "Em Andamento" ganham um fundo levemente colorido, melhorando a visualização rápida do estado.</p>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default App;

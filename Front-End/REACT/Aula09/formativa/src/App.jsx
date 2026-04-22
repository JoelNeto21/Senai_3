// Importa o React e os hooks useState e useEffect para controlar estado e efeitos colaterais.
import React, { useState, useEffect } from 'react';
// Importa o arquivo de estilos do componente.
import './App.css';

// Declara o componente principal da aplicação.
function App() {
  // Guarda o texto digitado no campo de tarefa.
  const [taskText, setTaskText] = useState("");

  const [taskName, setTaskName] = useState("");
  // Guarda qual tarefa está sendo editada.
  const [editingId, setEditingId] = useState(null);
  // Guarda o novo texto enquanto a tarefa está em edição.
  const [editingText, setEditingText] = useState("");
  // Guarda a tarefa que está aguardando confirmação de exclusão.
  const [taskToDelete, setTaskToDelete] = useState(null);
  // Guarda a prioridade escolhida no seletor.
  const [priority, setPriority] = useState("Baixa");
  // Guarda a lista de tarefas em memória.
  const [taskList, setTaskList] = useState(() => {
    try {
      // Tenta recuperar tarefas já salvas de execuções anteriores.
      const saved = localStorage.getItem("@taskflow_data");
      // Se existir conteúdo, converte de texto JSON para array; senão, começa vazio.
      return saved ? JSON.parse(saved) : [];
    } catch {
      // Se o JSON estiver inválido ou corrompido, evita quebrar a aplicação.
      return [];
    }
  });
  // Guarda o filtro atual da interface.
  const [filter, setFilter] = useState("Todas");
  // Define a ordem de prioridade para ordenação da lista.
  const priorityOrder = { Alta: 0, Média: 1, Baixa: 2 };

  // Executa sempre que a lista de tarefas mudar.
  useEffect(() => {
    // Salva a lista atual no localStorage.
    // Esse efeito roda automaticamente sempre que taskList muda.
    localStorage.setItem("@taskflow_data", JSON.stringify(taskList));
  }, [taskList]);

  // Função chamada quando o formulário é enviado.
  const addTask = (e) => {
    // Impede o recarregamento padrão da página.
    e.preventDefault();
    // Não cria tarefa vazia ou com espaços em branco.
    if (!taskText.trim()) return;
    // Monta o objeto da nova tarefa.
    const newTask = {
      // Gera um identificador único.
      id: crypto.randomUUID(),
      // Guarda o texto digitado.
      text: taskText,
      // Guarda a prioridade selecionada.
      priority: priority,
      // Começa como não concluída.
      completed: false,
      // Registra a data de criação.
      createdAt: new Date().toLocaleDateString()
    };
    // Adiciona a nova tarefa no início da lista.
    // O spread (...) copia as tarefas antigas e mantém imutabilidade do estado.
    setTaskList([newTask, ...taskList]);
    // Limpa o campo de texto depois de criar.
    setTaskText("");
  };

  // Alterna o estado de concluída de uma tarefa.
  const toggleTask = (id) => {
    // Percorre a lista e troca apenas a tarefa escolhida.
    // map cria um novo array sem alterar diretamente o estado anterior.
    setTaskList(taskList.map(t =>
      t.id === id ? { ...t, completed: !t.completed } : t
    ));
  };

  // Remove uma tarefa da lista.
  const deleteTask = (id) => {
    // Mantém somente as tarefas cujo id seja diferente do selecionado.
    setTaskList(taskList.filter(t => t.id !== id));
    setTaskToDelete(null);
  };

  // Abre o modal de confirmação para uma tarefa específica.
  const confirmDelete = (item) => {
    setTaskToDelete(item);
  };

  // Fecha o modal sem excluir nada.
  const cancelDelete = () => {
    setTaskToDelete(null);
  };

  // Inicia a edição de uma tarefa.
  const editTask = (item) => {
    // Coloca a tarefa no modo de edição e preenche o input com o texto atual.
    setEditingId(item.id);
    setEditingText(item.text);
  };

  // Salva a alteração feita na tarefa em edição.
  const saveTask = (id) => {
    // remove espaços extras do começo/fim para evitar salvar texto "vazio".
    const nextText = editingText.trim();

    if (!nextText) return;

    // Atualiza somente o texto da tarefa em edição, mantendo o restante igual.
    setTaskList(taskList.map(t => (
      t.id === id ? { ...t, text: nextText } : t
    )));

    setEditingId(null);
    setEditingText("");
  };

  // Cancela a edição sem alterar a tarefa.
  const cancelEdit = () => {
    setEditingId(null);
    setEditingText("");
  };

  // Aplica o filtro de status e também o texto da busca na lista completa.
  const filteredTasks = taskList
    .filter(t => {
      // Normaliza a busca para minúsculas e evita falha com espaços acidentais.
      const search = taskName.trim().toLowerCase();
      // Verifica se o texto da tarefa contém o termo pesquisado.
      const matchesSearch = !search || t.text.toLowerCase().includes(search);

      // Se o filtro for pendentes, mostra apenas as que não foram concluídas.
      if (filter === "Pendentes") return !t.completed && matchesSearch;
      // Se o filtro for concluídas, mostra apenas as concluídas.
      if (filter === "Concluídas") return t.completed && matchesSearch;
      // Caso contrário, mostra todas que batem com a busca.
      return matchesSearch;
    })
    // Ordena por prioridade usando o objeto priorityOrder como "ranking".
    // Quanto menor o número, mais alta a prioridade na listagem.
    .sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);

  // Renderiza a interface do componente.
  return (
    // Container principal da aplicação.
    <div className="app-container">
      {/* Cabeçalho com nome e descrição do sistema */}
      <header>
        <h1>TaskFlow</h1>
        <p>Gestão de Produtividade</p>
      </header>

      {/* Área do formulário para criar novas tarefas */}
      <section className="form-section">
        {/* Quando o form é enviado, a função addTask é executada */}
        <form onSubmit={addTask}>
          {/* Campo para digitar o nome da tarefa */}
          <input
            value={taskText}
            onChange={(e) => setTaskText(e.target.value)}
            placeholder="Descrição da tarefa..."
          />
          {/* Seleção da prioridade da tarefa */}
          <select value={priority} onChange={(e) => setPriority(e.target.value)}>
            <option value="Baixa">Baixa</option>
            <option value="Média">Média</option>
            <option value="Alta">Alta</option>
          </select>
          {/* Botão para criar a tarefa */}
          <button type="submit">Criar</button>
        </form>
      </section>

      {/* Área dos botões de filtro */}
      <section className="filter-section">
        <input
          value={taskName}
          onChange={(n) => setTaskName(n.target.value)}
          placeholder="🔍︎ Pesquisar tarefa"
        />
        {/* Cria um botão para cada tipo de filtro */}
        {["Todas", "Pendentes", "Concluídas"].map(f => (
          <button
            key={f}
            className={filter === f ? "active" : ""}
            onClick={() => setFilter(f)}
          >
            {f}
          </button>
        ))}
      </section>

      {/* Área onde as tarefas são exibidas em grade */}
      <main className="task-grid">
        {/* Percorre a lista filtrada e desenha um cartão para cada tarefa */}
        {filteredTasks.map(item => (
          // Cada tarefa recebe uma chave única e classes condicionais de estilo.
          <div key={item.id} className={`task-card ${item.priority.toLowerCase()} ${item.completed
            ? 'done' : ''}`}>
            {/* Bloco com as informações principais da tarefa */}
            <div className="task-content">
              {editingId === item.id ? (
                <div className="edit-row">
                  <input
                    value={editingText}
                    onChange={(e) => setEditingText(e.target.value)}
                    autoFocus
                  />
                  <button type="button" onClick={cancelEdit} className="edit-cancel">
                    X
                  </button>
                </div>
              ) : (
                <h3>{item.text}</h3>
              )}
              <span>Prioridade: {item.priority}</span>
              <small>Criada em: {item.createdAt}</small>
            </div>
            {/* Bloco com as ações possíveis para a tarefa */}
            <div className="task-actions">
              {/* Botão para remover a tarefa */}
              <button type="button" onClick={() => confirmDelete(item)} className="delete">
                Remover
              </button>
              {/* Botão para alternar entre concluída e reaberta */}
              <button type="button" onClick={() => toggleTask(item.id)} className="done">
                {item.completed ? "Reabrir" : "Concluir"}
              </button>
              {editingId === item.id ? (
                <>
                  <button type="button" onClick={() => saveTask(item.id)} className="edit">
                    Salvar
                  </button>
                </>
              ) : (
                <button type="button" onClick={() => editTask(item)} className="edit">
                  Editar
                </button>
              )}
            </div>
          </div>
        ))}
      </main>

      {/* Modal de confirmação de exclusão */}
      {taskToDelete && (
        <div className="modal-overlay" onClick={cancelDelete}>
          {/* stopPropagation impede que o clique dentro da caixa feche o modal */}
          <div className="modal-box" onClick={(e) => e.stopPropagation()}>
            <h2>Confirmar exclusão</h2>
            <p>Tem certeza que deseja excluir a tarefa “{taskToDelete.text}”?</p>
            <div className="modal-actions">
              <button type="button" onClick={cancelDelete} className="modal-cancel">
                Cancelar
              </button>
              <button type="button" onClick={() => deleteTask(taskToDelete.id)} className="modal-delete">
                Excluir
              </button>
            </div>
          </div>
        </div>
      )}
    </div>

  );
}

// Exporta o componente para ser usado em outros arquivos.
export default App;
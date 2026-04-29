import React, { useState, useEffect } from 'react';
import './App.css';

function App() {
  const [taskText, setTaskText] = useState("");
  const [taskName, setTaskName] = useState("");
  const [editingId, setEditingId] = useState(null);
  const [editingText, setEditingText] = useState("");
  const [taskToDelete, setTaskToDelete] = useState(null);
  const [priority, setPriority] = useState("Baixa");

  // Carrega as tarefas salvas no localStorage na inicialização.
  const [taskList, setTaskList] = useState(() => {
    try {
      const saved = localStorage.getItem("@taskflow_data");
      return saved ? JSON.parse(saved) : [];
    } catch {
      return [];
    }
  });
  const [filter, setFilter] = useState("Todas");

  // Ordenação por Prioridade
  const priorityOrder = { Alta: 0, Média: 1, Baixa: 2 };

  useEffect(() => {
    localStorage.setItem("@taskflow_data", JSON.stringify(taskList));
  }, [taskList]);

  const addTask = (e) => {
    e.preventDefault();
    if (!taskText.trim()) return;
    const newTask = {
      id: crypto.randomUUID(),
      text: taskText,
      priority: priority,
      completed: false,
      createdAt: new Date().toLocaleDateString()
    };
    setTaskList([newTask, ...taskList]);
    setTaskText("");
  };

  const toggleTask = (id) => {
    setTaskList(taskList.map(t =>
      t.id === id ? { ...t, completed: !t.completed } : t
    ));
  };

  const deleteTask = (id) => {
    setTaskList(taskList.filter(t => t.id !== id));
    setTaskToDelete(null);
  };

  const confirmDelete = (item) => {
    setTaskToDelete(item);
  };

  const cancelDelete = () => {
    setTaskToDelete(null);
  };

  const editTask = (item) => {
    setEditingId(item.id);
    setEditingText(item.text);
  };

  const saveTask = (id) => {
    const nextText = editingText.trim();

    if (!nextText) return;

    setTaskList(taskList.map(t => (
      t.id === id ? { ...t, text: nextText } : t
    )));

    setEditingId(null);
    setEditingText("");
  };

  const cancelEdit = () => {
    setEditingId(null);
    setEditingText("");
  };

  // Combina busca, filtro de status e ordenação por prioridade.
  const filteredTasks = taskList
    .filter(t => {
      const search = taskName.trim().toLowerCase();
      const matchesSearch = !search || t.text.toLowerCase().includes(search);

      if (filter === "Pendentes") return !t.completed && matchesSearch;
      if (filter === "Concluídas") return t.completed && matchesSearch;
      return matchesSearch;
    })
    // Ordenação
    .sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
    // O .sort() compara dois itens subtraindo seus pesos
    // ex.: Se Alta=0 e Baixa=2, então 0 - 2 = -2 (negativo), o que faz Alta vir antes.

  return (
    <div className="app-container">
      <header>
        <h1>TaskFlow</h1>
        <p>Gestão de Produtividade</p>
      </header>

      <section className="form-section">
        <form onSubmit={addTask}>
          <input
            value={taskText}
            onChange={(e) => setTaskText(e.target.value)}
            placeholder="Descrição da tarefa..."
          />
          <select value={priority} onChange={(e) => setPriority(e.target.value)}>
            <option value="Baixa">Baixa</option>
            <option value="Média">Média</option>
            <option value="Alta">Alta</option>
          </select>
          <button type="submit">Criar</button>
        </form>
      </section>

      <section className="filter-section">
        <input
          value={taskName}
          onChange={(n) => setTaskName(n.target.value)}
          placeholder="🔍︎ Pesquisar tarefa"
        />
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

      <main className="task-grid">
        {filteredTasks.map(item => (
          <div key={item.id} className={`task-card ${item.priority.toLowerCase()} ${item.completed
            ? 'done' : ''}`}>
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
            <div className="task-actions">
              <button type="button" onClick={() => confirmDelete(item)} className="delete">
                Remover
              </button>
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

      {taskToDelete && (
        <div className="modal-overlay" onClick={cancelDelete}>
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

export default App;
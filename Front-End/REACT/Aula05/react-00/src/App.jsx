import './App.css'

function Saudacao({nome}){
  return (
    <div style={{backgroundColor: '#f0f0f0', padding: '10px', borderRadius: '8px', marginBottom: '10px'}}>
      <h2 style={{color: '#007bff'}}>Olá, Professor <mark style={{padding: '0 7px', backgroundColor: '#007bff', color: '#ffffff'}}>{nome}</mark></h2>
      <p>Este componente foi criado separadamente</p>
    </div>
  )
}

function Lista(){
  return (
    <div style={{backgroundColor: '#f0f0f0', padding: '10px', borderRadius: '8px', marginBottom: '10px'}}>
      <h2 style={{color: '#00da7f'}}>Lista de Tarefas</h2>
      <ul style={{width: 'fit-content', margin: '0 auto', textAlign: 'left'}}>
        <li>Estudar 📝</li>
        <li>Estudar ✍️</li>
        <li>Estudar 📚</li>
      </ul>
    </div>
  )
}

function Table(){
  return (
    <div style={{backgroundColor: '#f0f0f0', padding: '10px', borderRadius: '8px', marginBottom: '10px'}}>
      <h2 style={{color: '#ffa600'}}>Cronograma</h2>
      <table style={{margin: '0 auto'}}>
        <tr>
          <th style={{
            backgroundColor: '#ffa600', 
            color: '#ffffff', 
            width: '100px'}}>Segunda</th>
          <th style={{
            backgroundColor: '#ffa600', 
            color: '#ffffff', 
            width: '100px'}}>Terça</th>
          <th style={{
            backgroundColor: '#ffa600', 
            color: '#ffffff', 
            width: '100px'}}>Quarta</th>
          <th style={{
            backgroundColor: '#ffa600', 
            color: '#ffffff', 
            width: '100px'}}>Quinta</th>
          <th style={{
            backgroundColor: '#ffa600', 
            color: '#ffffff', 
            width: '100px'}}>Sexta</th>
        </tr>
        <tr>
          <td>Back-End</td>
          <td>Mobile</td>
          <td>Front-End</td>
          <td>IA</td>
          <td>Projeto</td>
        </tr>
      </table>
    </div>
  )
}

// Comentários
function App() {
  return (
    // Comentários
    <div>
      <h1>Olá, React!</h1>
      <p>Estou alterando meu primeiro componente.</p>
      <div style={{padding: '20px'}}>
        <h1>Minha Primeira Aula de React</h1>
        <hr/>
        {/* Comentários */}
        <Saudacao nome='Samuel'/>
        <Lista/>
        <Table/>
        <p>Note que consigo chamar o componente que eu quiser, quantas vezes eu quiser</p>
      </div>
    </div>
  )
}

export default App

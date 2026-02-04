const prompt = require("prompt-sync")();
const limparNomeContato = (nome) => nome.trim().replace(/\s+/g, ' ').toUpperCase();

let nome = prompt('Digite seu nome: ');

console.log(limparNomeContato(nome));
console.log(nome.trim().split(/\s+/).length);

const prompt = require("prompt-sync")();
const limparNomeContato = (nome) => nome.trim().toUpperCase();

let nome = prompt('Digite seu nome: ');

console.log(limparNomeContato(nome));
console.log(nome.split(' ').length);

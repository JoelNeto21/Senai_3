const prompt = require("prompt-sync")();

let hoje = new Date();
let evento = new Date(prompt("Data do evento (yy-mm-dd): "));

let ms = evento.getTime() - hoje.getTime();
let dia = ms / 24 / 60 / 60 / 1000;
let result = Math.ceil(dia);

console.log(`Faltam ${result} dias para o seu compromisso!`);

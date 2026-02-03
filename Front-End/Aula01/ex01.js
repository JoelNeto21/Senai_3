const prompt = require("prompt-sync")();
const input_time = () => parseInt(prompt('Qual é o horário da tarefa? '));
const input_priori = () => parseInt(prompt('Qual é o nivel de prioridade dessa tarefa? '));

let time = input_time();
if (time < 0 || time > 23) {
    console.log('[ERRO] : Digite um valor válido!');
    input_time();
}

let priori = input_priori();
if (priori < 1 || priori > 10) {
    console.log('[ERRO] : Digite um valor válido!');
    input_priori();
}

let periodo = "";

if (time >= 0 && time <= 11) {
  periodo = "Manhã";
} else if (time >= 12 && time <= 17) {
  periodo = "Tarde";
} else if (time >= 18 && time <= 23) {
  periodo = "Noite";
} else {
  console.log('[ERRO]');
}

if (priori > 8 && periodo == 'Manhã' || periodo == "Tarde") {
  console.log('Tarefa CRÍTICA/URGENTE');
} else if (priori >= 7 && priori < 9 && periodo == 'Manhã' || periodo == "Tarde") {
 console.log('Tarefa IMPORTANTE');
} else if (periodo == "Noite") {
 console.log('Tarefa NÃO IMPORTANTE');
} else {
  console.log('[ERRO]');
}

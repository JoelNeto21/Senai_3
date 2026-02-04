const prompt = require("prompt-sync")();
const input_time = () => parseInt(prompt('Qual é o horário da tarefa? '));
const input_priori = () => parseInt(prompt('Qual é o nivel de prioridade dessa tarefa? '));

let time = 0;

while (true) {
  time = input_time();
  if (time < 0 || time > 23) {
      console.log('[ERRO] : Digite um valor válido!');
  }
  else break;
}

let priori = 0;

while (true) {
  priori = input_priori();
  if (priori < 1 || priori > 10) {
      console.log('[ERRO] : Digite um valor válido!');
  }
  else break;
}

if (priori < 7 || (time >= 18 && time <= 23)) {
 console.log('Tarefa NÃO IMPORTANTE');
} else if (priori < 9 && (time >= 0 && time <= 17)) {
 console.log('Tarefa IMPORTANTE');
} else if (priori > 8 && (time >= 0 && time <= 17)) {
  console.log('Tarefa CRÍTICA/URGENTE');
} else {
  console.log('[ERRO]');
}

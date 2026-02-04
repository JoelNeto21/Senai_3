const prompt = require("prompt-sync")();
const string = prompt("Digite os horários da Agenda (separados por vírgula ','): ");
const agendaHorarios = string.split(',').map(Number);
// const agendaHorarios = [8, 12, 25, 15, -2, 20];

let contagemValidos = 0;
let i = 1;

for (const time of agendaHorarios) {
    if(time < 0 || time > 23){
        console.log(`${i++}. [ERRO] ${time}h é inválido...`);
    }
    else {
        console.log(`${i++}. Compromisso agendado para as ${time}h`);
        contagemValidos++;
    }
}

console.log(`>>> ${contagemValidos} compromissos são válidos`);

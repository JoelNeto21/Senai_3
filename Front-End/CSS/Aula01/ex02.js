const prompt = require("prompt-sync")();
const soma = (a, b, c, d) => a - (b + c + d);

let salario = parseFloat(prompt('Digite o valor do salário: '));
let aluguel = parseFloat(prompt('Digite o valor do aluguel: '));
let alimentacao = parseFloat(prompt('Digite o valor do alimentação: '));
let lazer = parseFloat(prompt('Digite o valor do lazer: '));

let saldo = parseFloat(soma(salario, aluguel, alimentacao, lazer));
console.log(saldo);

if (saldo > 0) {
    console.log('Saldo Positivo');
} else if (saldo == 0) {
    console.log('No Limite');
} else {
    console.log('Saldo Negativo');
}

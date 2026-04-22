function converter() {
    const C = document.getElementById('C').value;
    const F = C * 1.8 + 32;
    const result = document.getElementById('F');
    result.textContent = `${F} °F`;

    if (F > 80) {
        document.body.style.backgroundColor = '#f8b840'
    } else if (F < 80) {
        document.body.style.backgroundColor = '#a1dafd'
    }
}

function limpar() {
    document.getElementById('C').value = '';
    document.getElementById('F').textContent = '';
    document.body.style.backgroundColor = '#ffffff'
}
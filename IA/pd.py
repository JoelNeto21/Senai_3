import pandas as pd

dados = {
    "Nome": ["Ana", "Bruno", "Carlos", "Diana"],
    "Idade": [20, 22, 19, 21],
    "Nota": [8.5, 7.0, 9.0, 8.0]
}

df = pd.DataFrame(dados)
print(df)

print("\nEstatísticas da Nota:")
print(df["Nota"].describe())

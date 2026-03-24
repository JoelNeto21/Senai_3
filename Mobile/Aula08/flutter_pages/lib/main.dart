import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: TelaInicial()));
}

// ------ TELA INICIAL ------

class TelaInicial extends StatelessWidget {
  const TelaInicial({super.key});
  final String nome = 'Joel';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text("Tela Inicial", style: TextStyle(color: Colors.white)),
        centerTitle: true,
        backgroundColor: Colors.teal,
      ),
      body: Center(
        child: ElevatedButton(
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.black,
            foregroundColor: Colors.white,
            elevation: 2,
            padding: EdgeInsets.symmetric(horizontal: 30, vertical: 20),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(30),
            ),
          ),
          child: Text("Segunda Tela ↪"),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => SegundaTela(nome: nome)),
            );
          },
        ),
      ),
    );
  }
}

// ------ SEGUNDA TELA ------

class SegundaTela extends StatelessWidget {
  SegundaTela({super.key, required this.nome});
  final String nome;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text("Segunda Tela", style: TextStyle(color: Colors.white)),
        centerTitle: true,
        backgroundColor: Colors.teal,
        iconTheme: IconThemeData(color: Colors.white),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              "Olá, $nome 👋",
              style: TextStyle(color: Colors.black, fontSize: 20),
            ),
            Text(
              "Bem Vindo à",
              style: TextStyle(color: Colors.black, fontSize: 30,),
            ),
            Text(
              "Segunda Tela!",
              style: TextStyle(
                color: Colors.black,
                fontSize: 40,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

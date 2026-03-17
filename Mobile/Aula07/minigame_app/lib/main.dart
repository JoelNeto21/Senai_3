import 'package:flutter/material.dart';
import 'dart:math';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: MiniGameApp()));
}

class MiniGameApp extends StatefulWidget {
  const MiniGameApp({super.key});

  @override
  State<MiniGameApp> createState() => _MiniGameAppState();
}

class _MiniGameAppState extends State<MiniGameApp> {
  IconData iconeComputador = Icons.monitor;
  String resultado = "Escolha uma opção";
  int pontosJogador = 0;
  int pontosComputador = 0;
  List opcoes = ["pedra", "papel", "tesoura"];

  void jogar(String escolhaUsuario) {
    final numero = Random().nextInt(3);
    final escolhaPc = opcoes[numero];

    setState(() {
      if (escolhaPc == "pedra") {
        iconeComputador = Icons.landscape;
      } else if (escolhaPc == "papel") {
        iconeComputador = Icons.pan_tool;
      } else {
        iconeComputador = Icons.content_cut;
      }

      if (escolhaUsuario == escolhaPc) {
        resultado = "Empate";
      } else if ((escolhaUsuario == "pedra" && escolhaPc == "tesoura") ||
          (escolhaUsuario == "papel" && escolhaPc == "pedra") ||
          (escolhaUsuario == "tesoura" && escolhaPc == "papel")) {
        pontosJogador++;
        resultado = "Você venceu!";
      } else {
        pontosComputador++;
        resultado = "Computador venceu!";
      }

      if (pontosJogador >= 3) {
        iconeComputador = Icons.person_pin_rounded;
        resultado = "Você ganhou o campeonato!";
        pontosJogador = 0;
        pontosComputador = 0;
      } else if (pontosComputador >= 3) {
        iconeComputador = Icons.monitor;
        resultado = "Computador ganhou o campeonato!";
        pontosJogador = 0;
        pontosComputador = 0;
      }
    });
  }

  void resetarPlacar() {
    setState(() {
      pontosJogador = 0;
      pontosComputador = 0;
      iconeComputador = Icons.monitor;
      resultado = "Escolha uma opção";
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: Text("Pedra Papel Tesoura", style: TextStyle(color: Colors.white)), centerTitle: true, backgroundColor: Colors.black),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text("Computador"),
            Icon(iconeComputador, size: 100),
            Text(resultado, style: TextStyle(fontSize: 26)),
            Text("Você: $pontosJogador | PC: $pontosComputador"),
            SizedBox(height: 20),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                IconButton(
                  icon: Icon(Icons.landscape),
                  onPressed: () => jogar("pedra"),
                ),
                IconButton(
                  icon: Icon(Icons.pan_tool),
                  onPressed: () => jogar("papel"),
                ),
                IconButton(
                  icon: Icon(Icons.content_cut),
                  onPressed: () => jogar("tesoura"),
                ),
              ],
            ),
            SizedBox(height: 20),
            ElevatedButton.icon(
              onPressed: resetarPlacar,
              icon: Icon(Icons.refresh),
              label: Text("Resetar Placar"),
            ),
          ],
        ),
      ),
    );
  }
}

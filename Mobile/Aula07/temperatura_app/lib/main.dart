import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(debugShowCheckedModeBanner: false, home: TemperaturaApp()),
  );
}

class TemperaturaApp extends StatefulWidget {
  const TemperaturaApp({super.key});

  @override
  State<TemperaturaApp> createState() => _TemperaturaAppState();
}

class _TemperaturaAppState extends State<TemperaturaApp> {
  int temperatura = 20;

  Color corFundo = Colors.green;
  IconData icone = Icons.wb_sunny;
  String status = 'Agradável';

  void atualizar() {
    if (temperatura < 15) {
      corFundo = Colors.blue;
      icone = Icons.ac_unit;
      status = "Frio";
    } else if (temperatura < 30) {
      corFundo = Colors.green;
      icone = Icons.wb_sunny;
      status = "Agradável";
    } else {
      corFundo = Colors.red;
      icone = Icons.local_fire_department;
      status = "Quente";
    }
  }

  void aumentar() {
    setState(() {
      temperatura++;
    });
    atualizar();
  }

  void diminuir() {
    setState(() {
      temperatura--;
    });
    atualizar();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: corFundo,
      appBar: AppBar(
        title: Text('Temperatura'),
        centerTitle: true,
        backgroundColor: Colors.white,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(icone, size: 100, color: Colors.white),
                Text(status, style: TextStyle(color: Colors.white, fontSize: 28)),
                SizedBox(height: 100),
                Text("$temperatura °C", style: TextStyle(color: Colors.white, fontSize: 40)),
                SizedBox(height: 20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    ElevatedButton(
                      onPressed: diminuir,
                      child: Icon(Icons.remove, color: Colors.black),
                    ),
                    SizedBox(width: 20),
                    ElevatedButton(onPressed: aumentar, child: Icon(Icons.add, color: Colors.black)),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

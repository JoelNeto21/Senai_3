import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(
      debugShowCheckedModeBanner: false, 
      home: PaginaContador()
    ),
  );
}

class PaginaContador extends StatefulWidget {
  @override
  _PaginaContadorState createState() => _PaginaContadorState();
}

class _PaginaContadorState extends State<PaginaContador> {
  int contador = 0;

  void increment() {
    setState(() {
      contador++;
    });
  }

  void decrement() {
    setState(() {
      contador--;
    });
  }

  void reset() {
    setState(() {
      contador = 0;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Meu App Interativo'),
        centerTitle: true,
        backgroundColor: Colors.amber.shade500,
      ),
      body: Center(
        child: Text("Cliques: $contador", style: TextStyle(fontSize: 30)),
      ),
      floatingActionButton: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          FloatingActionButton(
            onPressed: increment,
            backgroundColor: Colors.green[600],
            child: Icon(Icons.add, color: Colors.black),
          ),
          SizedBox(width: 10),
          FloatingActionButton(
            onPressed: reset,
            backgroundColor: Colors.black,
            child: Icon(Icons.exposure_zero, color: Colors.white),
          ),
          SizedBox(width: 10),
          FloatingActionButton(
            onPressed: decrement,
            backgroundColor: Colors.deepOrange,
            child: Icon(Icons.remove, color: Colors.black),
          ),
        ],
      ),
    );
  }
}

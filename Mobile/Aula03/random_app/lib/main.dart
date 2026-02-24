import 'package:flutter/material.dart';
import 'dart:math';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: NumberRandom()));
}

class NumberRandom extends StatefulWidget {
  const NumberRandom({super.key});

  @override
  _NumberRandomState createState() => _NumberRandomState();
}

class _NumberRandomState extends State<NumberRandom> {
  int contador = 0;

  void sortear() {
    setState(() {
      contador = Random().nextInt(11);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Sorteador'),
        centerTitle: true,
        backgroundColor: Colors.amber.shade500,
      ),
      body: Center(
        child: Text('$contador', style: TextStyle(fontSize: 30)),
      ),
      floatingActionButton: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          FloatingActionButton(
            onPressed: sortear,
            backgroundColor: Colors.deepOrange,
            child: Icon(Icons.auto_awesome, color: Colors.white),
          ),
        ],
      ),
    );
  }
}

import 'package:flutter/material.dart';

void main() {
  runApp(MeuApp());
}

class MeuApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(
          title: Text(
            'Lista de Tarefas',
            style: TextStyle(color: Colors.white),
          ),
          backgroundColor: Colors.black,
        ),
        // body: Center(child: Text('Hello World!'))
        body: ListView(
          children: [
            ListTile(
              leading: Icon(Icons.radio_button_unchecked),
              title: Text('Estudar Flutter'),
              trailing: Icon(Icons.delete),
            ),
            ListTile(
              leading: Icon(Icons.radio_button_unchecked),
              title: Text('Praticar Dart'),
              trailing: Icon(Icons.delete),
            ),
            ListTile(
              leading: Icon(Icons.radio_button_unchecked),
              title: Text('Criar App'),
              trailing: Icon(Icons.delete),
            ),
          ],
        ),
        backgroundColor: Colors.grey,
        floatingActionButton: FloatingActionButton(
          onPressed: () {},
          backgroundColor: Colors.black,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(15),
          ),
          child: Icon(Icons.add, color: Colors.white)
        ),
      ),
    );
  }
}

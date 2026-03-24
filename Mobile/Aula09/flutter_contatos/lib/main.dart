import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: Contatos()));
}

class Contatos extends StatelessWidget {
  const Contatos({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text('Lista de Contatos', style: TextStyle(color: Colors.white)),
        centerTitle: true,
        backgroundColor: Colors.black,
      ),
      body: ListView(
        children: [
          SizedBox(height: 10),
          ListTile(
            leading: Icon(Icons.person, color: Colors.red),
            title: Text('Ian Hickson'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => Detalhes(i: 1)),
              );
            },
          ),
          SizedBox(height: 10),
          ListTile(
            leading: Icon(Icons.person, color: Colors.green),
            title: Text('Kasper Lund'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => Detalhes(i: 2)),
              );
            },
          ),
          SizedBox(height: 10),
          ListTile(
            leading: Icon(Icons.person, color: Colors.blue),
            title: Text('Lars Bak'),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => Detalhes(i: 3)),
              );
            },
          ),
        ],
      ),
      // floatingActionButton: FloatingActionButton(
      //   onPressed: () {},
      //   backgroundColor: Colors.black,
      //   shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
      //   child: Icon(Icons.add, color: Colors.white),
      // ),
    );
  }
}

class Detalhes extends StatelessWidget {
  Detalhes({super.key, required this.i});
  int i;

  Color cor() {
    if (i == 1) return Colors.red;
    if (i == 2) return Colors.green;
    return Colors.blue;
  }

  String nome() {
    if (i == 1) return 'Ian Hickson';
    if (i == 2) return 'Kasper Lund';
    return 'Lars Bak';
  }

  String telefone() {
    if (i == 1) return '(11) 11111-1111';
    if (i == 2) return '(22) 22222-2222';
    return '(33) 33333-3333';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text('Detalhes', style: TextStyle(color: Colors.white)),
        centerTitle: true,
        backgroundColor: cor(),
        iconTheme: IconThemeData(color: Colors.white),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              nome(),
              style: TextStyle(fontSize: 30, fontWeight: FontWeight(500)),
            ),
            Text(telefone(), style: TextStyle(fontSize: 30)),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        backgroundColor: Colors.black,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        child: Icon(Icons.phone, color: Colors.white),
      ),
    );
  }
}

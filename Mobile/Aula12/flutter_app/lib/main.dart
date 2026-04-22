import 'package:flutter/material.dart';
import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: AppBanco()));
}

class AppBanco extends StatefulWidget {
  const AppBanco({super.key});

  @override
  State<AppBanco> createState() => _AppBancoState();
}

class _AppBancoState extends State<AppBanco> {
  TextEditingController controller = TextEditingController();
  List<Map<String, dynamic>> tarefas = [];

  Future<Database> criarBanco() async {
    final caminho = await getDatabasesPath();
    final path = join(caminho, "banco.db");

    return openDatabase(
      path,
      onCreate: (db, version) {
        return db.execute(
          "CREATE TABLE tarefas(id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT)",
        );
      },
      version: 1,
    );
  }

  Future<void> listarTarefa(String nome) async {
    final db = await criarBanco();
    await db.insert("tarefas", {"nome": nome});

    carregarTarefa();
  }

  Future<void> carregarTarefa() async {
    final db = await criarBanco();
    final lista = await db.query("tarefas");

    setState(() {
      tarefas = lista;
    });
  }

  Future<void> deletarTarefa(int id) async {
    final db = await criarBanco();
    await db.delete("tarefas", where: "id = ?", whereArgs: [id]);

    carregarTarefa();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.deepOrange,
        title: Text("Title", style: TextStyle(color: Colors.white)),
        centerTitle: true,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text("Text", style: TextStyle(fontSize: 30)),
            Text("Text", style: TextStyle(fontSize: 30)),
          ],
        ),
      ),
    );
  }
}

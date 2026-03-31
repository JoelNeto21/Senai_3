import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: Nota()));
}

class Nota extends StatefulWidget {
  const Nota({super.key});

  @override
  State<Nota> createState() => _NotaState();
}

class _NotaState extends State<Nota> {
  List<String> notas = [];
  TextEditingController controller = TextEditingController();

  void adicionarNota() {
    if (controller.text.isNotEmpty) {
      setState(() {
        notas.add(controller.text);
        controller.clear();
      });
      salvarNotas();
    }
  }

  void salvarNotas() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.setStringList("notas", notas);
  }

  void removerNota(int index) {
    setState(() {
      notas.removeAt(index);
    });
    salvarNotas();
  }

  void carregarNotas() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      notas = prefs.getStringList("notas") ?? [];
    });
  }

  @override
  void initState() {
    super.initState();
    carregarNotas();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Minhas Notas")),
      body: Column(
        children: [
          Padding(
            padding: EdgeInsets.all(10),
            child: TextField(
              controller: controller,
              decoration: InputDecoration(
                labelText: "Digite uma nota",
                border: OutlineInputBorder(),
              ),
            ),
          ),
          ElevatedButton(onPressed: adicionarNota, child: Text("Salvar Nota")),
          Expanded(
            child: notas.isEmpty
                ? Center(child: Text("Nenhuma nota ainda"))
                : ListView.builder(
                    itemCount: notas.length,
                    itemBuilder: (context, index) {
                      return ListTile(
                        title: Text(notas[index]),
                        trailing: IconButton(
                          icon: Icon(Icons.delete),
                          onPressed: () => removerNota(index),
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}

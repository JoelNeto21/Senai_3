import 'package:flutter/material.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});
  @override
  Widget build(BuildContext context) {
    return const MaterialApp(
      debugShowCheckedModeBanner: false,
      home: TodoPage(),
    );
  }
}

class TodoPage extends StatefulWidget {
  const TodoPage({super.key});

  @override
  State<TodoPage> createState() => _TodoPageState();
}

class _TodoPageState extends State<TodoPage> {
  final List<String> tarefas = [];
  final FocusNode listener = FocusNode();
  final TextEditingController controller = TextEditingController();
  int i = 0;

  void adicionarTarefa([String? value]) {
    setState(() {
      if (controller.text.trim().isNotEmpty) {
        tarefas.add(controller.text);
        controller.text = '';
        listener.requestFocus();
        i++;
        // print(tarefas);
      }
    });
  }

  void removerTarefa(int index) {
    setState(() {
      tarefas.removeAt(index);
      i--;
    });
  }

  @override
  void dispose() {
    controller.dispose();
    listener.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Lista de Tarefas ($i)'), centerTitle: true),
      body: Column(
        children: [
          TextField(
            focusNode: listener,
            autofocus: true,
            controller: controller,
            onSubmitted: (value) => adicionarTarefa(),
          ),
          ElevatedButton(
            onPressed: adicionarTarefa,
            child: const Text("Adicionar"),
          ),
          Expanded(
            child: tarefas.isEmpty
                ? Center(child: Text('Nenhuma tarefa cadastrada'))
                : ListView.builder(
                    itemCount: tarefas.length,
                    itemBuilder: (context, index) {
                      return ListTile(
                        title: Text(tarefas[index]),
                        trailing: IconButton(
                          icon: const Icon(Icons.delete),
                          onPressed: () => removerTarefa(index),
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

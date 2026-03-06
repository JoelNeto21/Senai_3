import 'package:flutter/material.dart';

void main() {
  runApp(const MyApp());
}

class Tarefa {
  String nome;
  bool concluida;
  Tarefa({required this.nome, this.concluida = false});
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      home: const TodoPage(),
    );
  }
}

class TodoPage extends StatefulWidget {
  const TodoPage({super.key});
  @override
  State<TodoPage> createState() => _TodoPageState();
}

class _TodoPageState extends State<TodoPage> {
  final List<Tarefa> tarefas = [];
  final FocusNode listener = FocusNode();
  final TextEditingController controller = TextEditingController();

  void adicionarTarefa([String? value]) {
    setState(() {
      if (controller.text.trim().isNotEmpty) {
        tarefas.add(Tarefa(nome: controller.text.trim()));
        controller.text = '';
        listener.requestFocus();
      }
    });
  }

  void alternarTarefa(int index) {
    setState(() {
      tarefas[index].concluida = !tarefas[index].concluida;
    });
  }

  void removerTarefa(int index) {
    setState(() {
      tarefas.removeAt(index);
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
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        centerTitle: true,
        // 1. Usamos o Stack no title para sobrepor o contador ao texto
        title: Stack(
          clipBehavior: Clip.none, // Permite que o círculo saia um pouco para fora dos limites do texto
          children: [
            const Text('Minhas Tarefas'),
            // 2. Só mostra o Badge se houver tarefas
            if (tarefas.isNotEmpty)
              Positioned(
                top: -5,    // Sobe o círculo para cima do texto
                right: -15, // Joga o círculo para a direita do texto
                child: Container(
                  padding: const EdgeInsets.all(4),
                  decoration: const BoxDecoration(
                    color: Colors.red,
                    shape: BoxShape.circle,
                  ),
                  constraints: const BoxConstraints(
                    minWidth: 20,
                    minHeight: 20,
                  ),
                  child: Center(
                    child: Text(
                      '${tarefas.length}',
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 10, // Texto menor para parecer notificação
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ),
          ],
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: TextField(
                    focusNode: listener,
                    autofocus: true,
                    controller: controller,
                    decoration: const InputDecoration(
                      labelText: 'O que precisa ser feito?',
                      border: OutlineInputBorder(),
                      prefixIcon: Icon(Icons.add_task),
                    ),
                    onSubmitted: (value) => adicionarTarefa(),
                  ),
                ),
                const SizedBox(width: 8),
                ElevatedButton(
                  onPressed: adicionarTarefa,
                  style: ElevatedButton.styleFrom(
                    minimumSize: const Size(0, 56),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                  child: const Icon(Icons.add),
                ),
              ],
            ),
            const SizedBox(height: 20),
            Expanded(
              child: tarefas.isEmpty
                  ? const Center(child: Text('Nenhuma tarefa cadastrada.'))
                  : ListView.builder(
                      itemCount: tarefas.length,
                      itemBuilder: (context, index) {
                        final item = tarefas[index];
                        return Card(
                          elevation: 2,
                          margin: const EdgeInsets.symmetric(vertical: 4),
                          child: CheckboxListTile(
                            controlAffinity: ListTileControlAffinity.leading,
                            title: Text(
                              item.nome,
                              style: TextStyle(
                                decoration: item.concluida 
                                  ? TextDecoration.lineThrough 
                                  : TextDecoration.none,
                                color: item.concluida ? Colors.grey : Colors.black,
                              ),
                            ),
                            value: item.concluida,
                            onChanged: (bool? valor) => alternarTarefa(index),
                            secondary: IconButton(
                              icon: const Icon(Icons.delete, color: Colors.redAccent),
                              onPressed: () => removerTarefa(index),
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}
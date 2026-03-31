import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: SaveText()));
}

class SaveText extends StatefulWidget {
  const SaveText({super.key});

  @override
  State<SaveText> createState() => _SaveTextState();
}

class _SaveTextState extends State<SaveText> {
  TextEditingController controller = TextEditingController();
  String textoSalvo = "";

  void salvarTexto() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('texto', controller.text);

    setState(() {
      textoSalvo = controller.text;
    });
  }

  void carregarTexto() async {
    final prefs = await SharedPreferences.getInstance();

    setState(() {
      textoSalvo = prefs.getString('texto') ?? "";
    });
  }

  @override
  void initState() {
    super.initState();
    carregarTexto();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text("Salvar Dados", style: TextStyle(color: Colors.white)),
        centerTitle: true,
        backgroundColor: Colors.black,
      ),
      body: Padding(
        padding: EdgeInsets.all(20),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(
              controller: controller,
              decoration: InputDecoration(labelText: "Digite algo"),
            ),
            SizedBox(height: 20),
            ElevatedButton(onPressed: salvarTexto, child: Text("Salvar")),
            SizedBox(height: 20),
            Text("Salvo: $textoSalvo", style: TextStyle(fontSize: 20)),
          ],
        ),
      ),
    );
  }
}

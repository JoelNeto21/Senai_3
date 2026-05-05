import 'package:flutter/material.dart';
import 'package:sqflite_common_ffi/sqflite_ffi.dart';
import 'package:path/path.dart' as p;
import 'dart:io';

// 🎨 Cores Apple Style
const Color _primaryBlack = Color(0xFF000000);
const Color _secondaryGray = Color(0xFF8E8E93);
const Color _lightGray = Color(0xFFF2F2F7);
const Color _dangerRed = Color(0xFFFF3B30);

// Inicializa FFI para desktop
void _initializeFFI() {
  if (Platform.isWindows || Platform.isLinux || Platform.isMacOS) {
    databaseFactory = databaseFactoryFfi;
  }
}

Future<Database> abrirBanco() async {
  final caminho = await getDatabasesPath();
  final path = p.join(caminho, 'banco_profissional.db');

  return openDatabase(
    path,
    version: 1,
    onCreate: (db, version) {
      return db.execute(
        'CREATE TABLE dados(id INTEGER PRIMARY KEY AUTOINCREMENT, titulo TEXT, descricao TEXT, data TEXT)',
      );
    },
  );
}

void main() {
  _initializeFFI();
  runApp(
    const MaterialApp(
      debugShowCheckedModeBanner: false,
      home: CadastroInteligente(),
      title: 'Cadastro Profissional',
    ),
  );
}

class CadastroInteligente extends StatefulWidget {
  const CadastroInteligente({super.key});

  @override
  State<CadastroInteligente> createState() => _CadastroInteligenteState();
}

class _CadastroInteligenteState extends State<CadastroInteligente> {
  TextEditingController tituloController = TextEditingController();
  TextEditingController descController = TextEditingController();

  List<Map<String, dynamic>> tarefas = [];
  late Database _database;

  Future<void> initDatabase() async {
    _database = await abrirBanco();
    await carregarItens();
  }

  Future<void> inserirItem() async {
    if (tituloController.text.trim().isEmpty) return;

    try {
      await _database.insert("dados", {
        "titulo": tituloController.text.trim(),
        "descricao": descController.text.trim(),
        "data": DateTime.now().toString().substring(0, 16),
      });

      tituloController.clear();
      descController.clear();
      await carregarItens();
    } catch (e) {
      debugPrint('Erro ao inserir: $e');
    }
  }

  Future<void> carregarItens() async {
    try {
      final lista = await _database.query("dados", orderBy: "titulo ASC");

      if (!mounted) return;
      setState(() {
        tarefas = lista;
      });
    } catch (e) {
      debugPrint('Erro ao carregar: $e');
    }
  }

  Future<void> deletarItem(int id) async {
    try {
      await _database.delete("dados", where: "id = ?", whereArgs: [id]);
      await carregarItens();
    } catch (e) {
      debugPrint('Erro ao deletar: $e');
    }
  }

  @override
  void initState() {
    super.initState();
    initDatabase();
  }

  @override
  void dispose() {
    tituloController.dispose();
    descController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text(
          'Cadastro',
          style: TextStyle(
            color: _primaryBlack,
            fontSize: 28,
            fontWeight: FontWeight.w700,
            letterSpacing: -0.5,
          ),
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            // Campos de Cadastro
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 24, 16, 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  TextField(
                    controller: tituloController,
                    style: const TextStyle(color: _primaryBlack, fontSize: 16),
                    minLines: 1,
                    maxLines: 2,
                    decoration: InputDecoration(
                      labelText: 'Título',
                      labelStyle: const TextStyle(
                        color: _secondaryGray,
                        fontSize: 14,
                      ),
                      filled: true,
                      fillColor: _lightGray,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide.none,
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: const BorderSide(
                          color: _primaryBlack,
                          width: 1.5,
                        ),
                      ),
                      contentPadding: const EdgeInsets.all(16),
                    ),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: descController,
                    style: const TextStyle(color: _primaryBlack, fontSize: 16),
                    minLines: 2,
                    maxLines: 4,
                    decoration: InputDecoration(
                      labelText: 'Descrição',
                      labelStyle: const TextStyle(
                        color: _secondaryGray,
                        fontSize: 14,
                      ),
                      filled: true,
                      fillColor: _lightGray,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide.none,
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: const BorderSide(
                          color: _primaryBlack,
                          width: 1.5,
                        ),
                      ),
                      contentPadding: const EdgeInsets.all(16),
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: inserirItem,
                      icon: const Icon(Icons.check_circle, size: 20),
                      label: const Text(
                        'Salvar',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _primaryBlack,
                        foregroundColor: Colors.white,
                        elevation: 0,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        minimumSize: const Size(double.infinity, 48),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const Divider(
              height: 1,
              color: _lightGray,
              indent: 0,
              endIndent: 0,
            ),
            // Listagem
            Expanded(
              child: tarefas.isEmpty
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(
                            Icons.inbox_outlined,
                            size: 48,
                            color: _secondaryGray.withValues(alpha: 0.5),
                          ),
                          const SizedBox(height: 12),
                          const Text(
                            'Nenhum item cadastrado',
                            style: TextStyle(
                              color: _secondaryGray,
                              fontSize: 16,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 12,
                      ),
                      itemCount: tarefas.length,
                      itemBuilder: (context, index) {
                        final item = tarefas[index];
                        return Padding(
                          padding: const EdgeInsets.symmetric(vertical: 6),
                          child: Card(
                            elevation: 0,
                            color: _lightGray,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: ListTile(
                              contentPadding: const EdgeInsets.all(16),
                              title: Text(
                                item['titulo'] ?? '',
                                style: const TextStyle(
                                  color: _primaryBlack,
                                  fontSize: 16,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                              subtitle: Padding(
                                padding: const EdgeInsets.only(top: 8),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      item['descricao'] ?? '',
                                      maxLines: 2,
                                      overflow: TextOverflow.ellipsis,
                                      style: const TextStyle(
                                        color: _secondaryGray,
                                        fontSize: 14,
                                        fontWeight: FontWeight.w400,
                                      ),
                                    ),
                                    const SizedBox(height: 6),
                                    Text(
                                      item['data'] ?? '',
                                      style: const TextStyle(
                                        color: _secondaryGray,
                                        fontSize: 12,
                                        fontWeight: FontWeight.w400,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              onTap: () async {
                                await Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (BuildContext context) => TelaEditar(item: item),
                                  ),
                                );
                                await carregarItens();
                              },
                              trailing: IconButton(
                                icon: const Icon(
                                  Icons.delete_outline,
                                  color: _dangerRed,
                                  size: 20,
                                ),
                                onPressed: () => deletarItem(item['id']),
                              ),
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

class TelaEditar extends StatefulWidget {
  final Map<String, dynamic> item;
  const TelaEditar({super.key, required this.item});

  @override
  State<TelaEditar> createState() => _TelaEditarState();
}

class _TelaEditarState extends State<TelaEditar> {
  late TextEditingController editTitulo;
  late TextEditingController editDesc;

  @override
  void initState() {
    super.initState();
    editTitulo = TextEditingController(text: widget.item['titulo']);
    editDesc = TextEditingController(text: widget.item['descricao']);
  }

  @override
  void dispose() {
    editTitulo.dispose();
    editDesc.dispose();
    super.dispose();
  }

  Future<void> atualizarNoBanco() async {
    if (editTitulo.text.trim().isEmpty) return;

    final db = await abrirBanco();
    await db.update(
      'dados',
      {'titulo': editTitulo.text.trim(), 'descricao': editDesc.text.trim()},
      where: 'id = ?',
      whereArgs: [widget.item['id']],
    );

    if (!mounted) return;
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.chevron_left, color: _primaryBlack),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Editar',
          style: TextStyle(
            color: _primaryBlack,
            fontSize: 28,
            fontWeight: FontWeight.w700,
            letterSpacing: -0.5,
          ),
        ),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextField(
                controller: editTitulo,
                style: const TextStyle(color: _primaryBlack, fontSize: 16),
                minLines: 1,
                maxLines: 2,
                decoration: InputDecoration(
                  labelText: 'Título',
                  labelStyle: const TextStyle(
                    color: _secondaryGray,
                    fontSize: 14,
                  ),
                  filled: true,
                  fillColor: _lightGray,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: BorderSide.none,
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(
                      color: _primaryBlack,
                      width: 1.5,
                    ),
                  ),
                  contentPadding: const EdgeInsets.all(16),
                ),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: editDesc,
                style: const TextStyle(color: _primaryBlack, fontSize: 16),
                minLines: 3,
                maxLines: 6,
                decoration: InputDecoration(
                  labelText: 'Descrição',
                  labelStyle: const TextStyle(
                    color: _secondaryGray,
                    fontSize: 14,
                  ),
                  filled: true,
                  fillColor: _lightGray,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: BorderSide.none,
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(
                      color: _primaryBlack,
                      width: 1.5,
                    ),
                  ),
                  contentPadding: const EdgeInsets.all(16),
                ),
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: atualizarNoBanco,
                  icon: const Icon(Icons.check_circle, size: 20),
                  label: const Text(
                    'Salvar Alterações',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: _primaryBlack,
                    foregroundColor: Colors.white,
                    elevation: 0,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    minimumSize: const Size(double.infinity, 48),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

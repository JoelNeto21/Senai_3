import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: SemaforoApp()));
}

class SemaforoApp extends StatefulWidget {
  const SemaforoApp({super.key});

  @override
  State<SemaforoApp> createState() => _SemaforoAppState();
}

class _SemaforoAppState extends State<SemaforoApp> {
  int estado = 0;

  void mudarEstado() {
    setState(() {
      estado++;
      if (estado > 2) estado = 0;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[300],
      appBar: AppBar(
        title: Text('Semáforo de Trânsito'),
        centerTitle: true,
        backgroundColor: Colors.white,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 120,
              height: 280,
              padding: EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: Colors.black,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Container(
                    width: 70,
                    height: 70,
                    decoration: BoxDecoration(
                      color: estado == 0 ? Colors.red : Colors.grey,
                      shape: BoxShape.circle,
                    ),
                  ),
                  SizedBox(height: 10),
                  Container(
                    width: 70,
                    height: 70,
                    decoration: BoxDecoration(
                      color: estado == 1 ? Colors.yellow : Colors.grey,
                      shape: BoxShape.circle,
                    ),
                  ),
                  SizedBox(height: 10),
                  Container(
                    width: 70,
                    height: 70,
                    decoration: BoxDecoration(
                      color: estado == 2 ? Colors.green : Colors.grey,
                      shape: BoxShape.circle,
                    ),
                  ),
                ],
              ),
            ),
            SizedBox(height: 30),
            Column(
              children: [
                Icon(
                  estado == 0 ? Icons.directions_walk : Icons.pan_tool,
                  size: 80,
                  color: estado == 0 ? Colors.green : Colors.red,
                ),
                Text(
                  estado == 0 ? "PEDESTRE: ATRAVESSE" : "PEDESTRE: AGUARDE",
                  style: TextStyle(fontSize: 20),
                ),
              ],
            ),
            SizedBox(height: 30),
            ElevatedButton(
              onPressed: mudarEstado,
              style: ElevatedButton.styleFrom(backgroundColor: Colors.white),
              child: Text(
                'Mudar Estado',
                style: TextStyle(color: Colors.black),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

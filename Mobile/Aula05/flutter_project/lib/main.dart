import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(debugShowCheckedModeBanner: false, home: InterruptorApp()),
  );
}

class InterruptorApp extends StatefulWidget {
  @override
  _InterruptorAppState createState() => _InterruptorAppState();
}

class _InterruptorAppState extends State<InterruptorApp> {
  bool estaAceso = false;

  void alternarLuz() {
    setState(() {
      estaAceso = !estaAceso;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: estaAceso ? Colors.black : Colors.white,
      appBar: AppBar(
        title: Text(
          'Interruptor',
          style: TextStyle(
            color: estaAceso ? Colors.black : Colors.white,
            fontWeight: FontWeight(600),
          ),
        ),
        centerTitle: true,
        backgroundColor: estaAceso ? Colors.white : Colors.black,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              estaAceso ? Icons.lightbulb : Icons.lightbulb_outline,
              color: estaAceso ? Colors.white : Colors.black,
              size: 100,
            ),
            ElevatedButton(
              onPressed: alternarLuz,
              style: ElevatedButton.styleFrom(
                backgroundColor: estaAceso ? Colors.white : Colors.black,
              ),
              child: estaAceso
                  ? Text('Acender', style: TextStyle(color: Colors.black))
                  : Text('Apagar', style: TextStyle(color: Colors.white)),
            ),
          ],
        ),
      ),
    );
  }
}

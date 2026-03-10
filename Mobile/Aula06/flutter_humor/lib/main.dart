import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(debugShowCheckedModeBanner: false, home: HumorApp()));
}

class HumorApp extends StatefulWidget {
  const HumorApp({super.key});
  
  @override
  State<HumorApp> createState() => _HumorAppState();
}

class _HumorAppState extends State<HumorApp> {
  int i = 0;

  void mudarHumor() {
    setState(() {
      i++;
      if (i > 2) i = 0;
    });
  }

  Color mudarBgColor() {
    if (i == 0) return Colors.green;
    if (i == 1) return Colors.blue;
    return Colors.red;
  }

  String mudarEmoji() {
    if (i == 0) return '😀';
    if (i == 1) return '😐';
    return '😡';
  }

  String mudarText() {
    if (i == 0) return 'Feliz';
    if (i == 1) return 'Neutro';
    return 'Bravo';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Humor',
          style: TextStyle(color: Colors.white, fontWeight: FontWeight(600)),
        ),
        centerTitle: true,
        backgroundColor: mudarBgColor(),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(mudarEmoji(), style: TextStyle(fontSize: 90)),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: mudarHumor,
              style: ElevatedButton.styleFrom(backgroundColor: Colors.black),
              child: Text(mudarText(), style: TextStyle(color: Colors.white)),
            ),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'dart:math';

void main() {
  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.dark,
  ));
  
  runApp(MaterialApp(
    debugShowCheckedModeBanner: false,
    title: 'Jo-Ken-Po Premium',
    theme: ThemeData.light().copyWith(
      scaffoldBackgroundColor: const Color(0xFFF8FAFC), 
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.transparent,
        elevation: 0,
        foregroundColor: Color(0xFF0F172A), 
      ),
      textTheme: const TextTheme(
        bodyMedium: TextStyle(fontFamily: 'Roboto', color: Color(0xFF334155)),
      ),
    ),
    home: const MiniGameApp(),
  ));
}

class MiniGameApp extends StatefulWidget {
  const MiniGameApp({super.key});

  @override
  State<MiniGameApp> createState() => _MiniGameAppState();
}

class _MiniGameAppState extends State<MiniGameApp> with TickerProviderStateMixin {
  String emojiComputador = '🤔';
  String emojiJogador = '👋';
  
  String resultado = "Faça sua jogada";
  Color corResultado = const Color(0xFF94A3B8);
  
  int pontosJogador = 0;
  int pontosComputador = 0;
  List<String> opcoes = ["pedra", "papel", "tesoura"];

  int vencedorRodada = 0; 
  bool isCampeao = false;

  final Color corPc = const Color(0xFFEF4444); 
  final Color corJogador = const Color(0xFF3B82F6); 
  final Color corEmpate = const Color(0xFFF59E0B); 

  void jogar(String escolhaUsuario) {
    HapticFeedback.lightImpact(); 

    final numero = Random().nextInt(3);
    final escolhaPc = opcoes[numero];

    setState(() {
      isCampeao = false; 

      emojiPcMap(escolhaPc);
      emojiJogadorMap(escolhaUsuario);

      if (escolhaUsuario == escolhaPc) {
        resultado = "EMPATE!";
        corResultado = corEmpate;
        vencedorRodada = 0;
      } else if ((escolhaUsuario == "pedra" && escolhaPc == "tesoura") ||
          (escolhaUsuario == "papel" && escolhaPc == "pedra") ||
          (escolhaUsuario == "tesoura" && escolhaPc == "papel")) {
        pontosJogador++;
        resultado = "+1 PARA VOCÊ!";
        corResultado = const Color(0xFF10B981); 
        vencedorRodada = 1;
        HapticFeedback.mediumImpact();
      } else {
        pontosComputador++;
        resultado = "+1 PARA O PC!";
        corResultado = corPc;
        vencedorRodada = -1;
        HapticFeedback.mediumImpact();
      }

      if (pontosJogador >= 3) {
        emojiJogador = '🏆';
        emojiComputador = '😵';
        resultado = "VOCÊ É O CAMPEÃO!";
        corResultado = corEmpate;
        isCampeao = true; 
        HapticFeedback.heavyImpact(); 
        pontosJogador = 0;
        pontosComputador = 0;
      } else if (pontosComputador >= 3) {
        emojiComputador = '🤖';
        emojiJogador = '💀';
        resultado = "GAME OVER";
        corResultado = const Color(0xFF475569); 
        HapticFeedback.heavyImpact();
        pontosJogador = 0;
        pontosComputador = 0;
      }
    });
  }

  void emojiPcMap(String escolha) {
    if (escolha == "pedra") emojiComputador = '🪨';
    else if (escolha == "papel") emojiComputador = '📄';
    else emojiComputador = '✂️';
  }

  void emojiJogadorMap(String escolha) {
    if (escolha == "pedra") emojiJogador = '🪨';
    else if (escolha == "papel") emojiJogador = '📄';
    else emojiJogador = '✂️';
  }

  void resetarPlacar() {
    HapticFeedback.selectionClick();
    setState(() {
      pontosJogador = 0;
      pontosComputador = 0;
      emojiComputador = '🤔';
      emojiJogador = '👋';
      resultado = "Faça sua jogada";
      corResultado = const Color(0xFF94A3B8);
      vencedorRodada = 0;
      isCampeao = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Jo-Ken-Po", style: TextStyle(fontWeight: FontWeight.w900, fontSize: 24, letterSpacing: -1)), 
        centerTitle: true,
      ),
      body: Stack(
        children: [
          SafeArea(
            child: Column(
              children: [
                const SizedBox(height: 10),
                
                // --- PLACAR ---
                Container(
                  padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 30),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(100),
                    boxShadow: [
                      BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 15, offset: const Offset(0, 5))
                    ]
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text("PC  ", style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey[400])),
                      Text("$pontosComputador", style: TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: corPc)),
                      const Padding(
                        padding: EdgeInsets.symmetric(horizontal: 20.0),
                        child: Text(":", style: TextStyle(color: Color(0xFFE2E8F0), fontSize: 20, fontWeight: FontWeight.bold)),
                      ),
                      Text("$pontosJogador", style: TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: corJogador)),
                      Text("  VOCÊ", style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey[400])),
                    ],
                  ),
                ),

                const SizedBox(height: 50),

                // --- ÁREA DE COMBATE LIVRE (SEM RETÂNGULOS) ---
                Expanded(
                  child: Stack(
                    alignment: Alignment.center,
                    children: [
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 20),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            // Avatar Computador
                            Expanded(child: _buildPlayerAvatar("COMPUTADOR", emojiComputador, corPc, vencedorRodada == -1, vencedorRodada == 1)),
                            
                            // Avatar Jogador
                            Expanded(child: _buildPlayerAvatar("VOCÊ", emojiJogador, corJogador, vencedorRodada == 1, vencedorRodada == -1)),
                          ],
                        ),
                      ),

                      // Emblema "VS" Flutuante Centralizado
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(color: Colors.black.withOpacity(0.08), blurRadius: 20, spreadRadius: 2, offset: const Offset(0, 5))
                          ]
                        ),
                        child: const Text(
                          "VS", 
                          style: TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: Color(0xFFCBD5E1), fontStyle: FontStyle.italic),
                        ),
                      ),
                    ],
                  ),
                ),

                // --- MENSAGEM DE RESULTADO ---
                AnimatedSwitcher(
                  duration: const Duration(milliseconds: 300),
                  transitionBuilder: (child, animation) => ScaleTransition(scale: animation, child: child),
                  child: Text(
                    resultado,
                    key: ValueKey(resultado),
                    style: TextStyle(
                      fontSize: 20, 
                      fontWeight: FontWeight.w900, 
                      color: corResultado,
                      letterSpacing: 0.5,
                    ),
                  ),
                ),

                const SizedBox(height: 40),

                // --- BOTÕES DE AÇÃO ---
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    BounceButton(emoji: '🪨', onTap: () => jogar("pedra")),
                    const SizedBox(width: 20),
                    BounceButton(emoji: '📄', onTap: () => jogar("papel")),
                    const SizedBox(width: 20),
                    BounceButton(emoji: '✂️', onTap: () => jogar("tesoura")),
                  ],
                ),
                
                const SizedBox(height: 40),

                // Botão Recomeçar 
                TextButton(
                  onPressed: resetarPlacar,
                  style: TextButton.styleFrom(
                    foregroundColor: const Color(0xFF94A3B8),
                    splashFactory: NoSplash.splashFactory,
                  ),
                  child: const Text("Zerar Placar", style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600, decoration: TextDecoration.underline)),
                ),
                const SizedBox(height: 30),
              ],
            ),
          ),
          
          if (isCampeao) const Positioned.fill(child: ConfettiShower()),
        ],
      ),
    );
  }

  // Novo Design de Avatar Orgânico (Círculos ao invés de retângulos)
  Widget _buildPlayerAvatar(String titulo, String emoji, Color corAura, bool isVencedor, bool isPerdedor) {
    // Calcula os tamanhos dinâmicos baseados no resultado
    double containerSize = isVencedor ? 150 : (isPerdedor ? 100 : 130);
    double emojiSize = isVencedor ? 75 : (isPerdedor ? 45 : 60);

    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Text(titulo, style: TextStyle(fontSize: 13, fontWeight: FontWeight.w800, color: const Color(0xFF64748B), letterSpacing: 1.5)),
        const SizedBox(height: 20),
        
        // Aura Circular Animada
        AnimatedContainer(
          duration: const Duration(milliseconds: 500),
          curve: Curves.elasticOut, // Efeito de "mola" elástica muito satisfatório
          height: containerSize,
          width: containerSize,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            color: isVencedor ? corAura.withOpacity(0.15) : Colors.white,
            border: Border.all(
              color: isVencedor ? corAura : Colors.transparent, 
              width: isVencedor ? 4 : 0
            ),
            boxShadow: [
              BoxShadow(
                color: isVencedor ? corAura.withOpacity(0.4) : Colors.black.withOpacity(0.05), 
                blurRadius: isVencedor ? 30 : 15, 
                spreadRadius: isVencedor ? 5 : 0,
                offset: const Offset(0, 8)
              )
            ],
          ),
          child: Center(
            child: AnimatedOpacity(
              duration: const Duration(milliseconds: 300),
              opacity: isPerdedor ? 0.3 : 1.0, 
              child: AnimatedSwitcher(
                duration: const Duration(milliseconds: 300),
                transitionBuilder: (Widget child, Animation<double> animation) {
                  // Adiciona uma leve rotação junto com o zoom para as mãos parecerem mais vivas
                  return RotationTransition(
                    turns: Tween<double>(begin: -0.05, end: 0.0).animate(animation),
                    child: ScaleTransition(scale: animation, child: child),
                  );
                },
                child: Text(emoji, key: ValueKey(emoji), style: TextStyle(fontSize: emojiSize)),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

// Botão com efeito Bounce
class BounceButton extends StatefulWidget {
  final String emoji;
  final VoidCallback onTap;

  const BounceButton({super.key, required this.emoji, required this.onTap});

  @override
  State<BounceButton> createState() => _BounceButtonState();
}

class _BounceButtonState extends State<BounceButton> with SingleTickerProviderStateMixin {
  late double _scale;
  late AnimationController _controller;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: const Duration(milliseconds: 100), lowerBound: 0.0, upperBound: 0.15)..addListener(() { setState(() {}); });
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    _scale = 1 - _controller.value;
    return GestureDetector(
      onTapDown: (_) => _controller.forward(),
      onTapUp: (_) {
        _controller.reverse();
        widget.onTap();
      },
      onTapCancel: () => _controller.reverse(),
      child: Transform.scale(
        scale: _scale,
        child: Container(
          decoration: BoxDecoration(
            color: Colors.white,
            shape: BoxShape.circle,
            boxShadow: [
              BoxShadow(color: Colors.black.withOpacity(0.08), blurRadius: 20, offset: const Offset(0, 8))
            ]
          ),
          padding: const EdgeInsets.all(24),
          child: Text(widget.emoji, style: const TextStyle(fontSize: 36)),
        ),
      ),
    );
  }
}

// Motor de partículas de confete
class ConfettiShower extends StatefulWidget {
  const ConfettiShower({super.key});

  @override
  State<ConfettiShower> createState() => _ConfettiShowerState();
}

class _ConfettiShowerState extends State<ConfettiShower> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  final List<Particle> _particles = [];
  final Random _random = Random();

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: const Duration(seconds: 4))..addListener(() { setState(() { _updateParticles(); }); });
    _generateParticles();
    _controller.forward();
  }

  void _generateParticles() {
    final colors = [Colors.red, Colors.blue, Colors.green, Colors.yellow, Colors.purple, Colors.orange];
    for (int i = 0; i < 80; i++) {
      _particles.add(Particle(
        x: _random.nextDouble() * 400, 
        y: -(_random.nextDouble() * 200), 
        vx: (_random.nextDouble() - 0.5) * 6, 
        vy: _random.nextDouble() * 5 + 3, 
        color: colors[_random.nextInt(colors.length)],
        size: _random.nextDouble() * 8 + 6,
        angle: _random.nextDouble() * pi * 2,
        spin: (_random.nextDouble() - 0.5) * 0.3,
      ));
    }
  }

  void _updateParticles() {
    for (var p in _particles) {
      p.x += p.vx;
      p.y += p.vy;
      p.angle += p.spin;
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return IgnorePointer( 
      child: CustomPaint(
        painter: ConfettiPainter(_particles),
        child: Container(),
      ),
    );
  }
}

class Particle {
  double x, y, vx, vy, size, angle, spin;
  Color color;
  Particle({required this.x, required this.y, required this.vx, required this.vy, required this.size, required this.angle, required this.spin, required this.color});
}

class ConfettiPainter extends CustomPainter {
  final List<Particle> particles;
  ConfettiPainter(this.particles);

  @override
  void paint(Canvas canvas, Size size) {
    for (var p in particles) {
      final paint = Paint()..color = p.color;
      canvas.save();
      double renderX = p.x % size.width; 
      canvas.translate(renderX, p.y);
      canvas.rotate(p.angle);
      canvas.drawRect(Rect.fromCenter(center: Offset.zero, width: p.size, height: p.size * 0.6), paint);
      canvas.restore();
    }
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => true;
}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex TCG | {{ ucfirst($pokemon['name'] ?? 'Desconhecido') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@400;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --type-color: {{ $primaryColor ?? '#A8A77A' }};
            --type-color-rgb: {{ $primaryColorRgb ?? '168, 167, 122' }};
            --glow-color: rgba(var(--type-color-rgb), 0.6);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #030509;
            overflow-x: hidden;
        }

        /* ── FUNDO CYBERPUNK ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(var(--type-color-rgb), 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(var(--type-color-rgb), 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .bg-scan {
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.08) 2px,
                rgba(0,0,0,0.08) 4px
            );
            pointer-events: none;
            z-index: 0;
        }

        .bg-vignette {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.85) 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* Partículas flutuantes */
        @keyframes float-up {
            0%   { transform: translateY(100vh) translateX(0px); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.4; }
            100% { transform: translateY(-20px) translateX(40px); opacity: 0; }
        }

        .particle {
            position: fixed;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: var(--type-color);
            animation: float-up linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        /* ── HEADER ── */
        .pokedex-header {
            font-family: 'Orbitron', monospace;
            letter-spacing: 0.2em;
        }

        .header-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--type-color), transparent);
        }

        /* ── SEARCH TERMINAL ── */
        .search-terminal {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid rgba(var(--type-color-rgb), 0.4);
            border-radius: 4px;
            backdrop-filter: blur(20px);
            position: relative;
            transition: border-color 0.3s;
        }

        .search-terminal:focus-within {
            border-color: var(--type-color);
            box-shadow: 0 0 20px var(--glow-color), inset 0 0 20px rgba(var(--type-color-rgb), 0.05);
        }

        .search-terminal::before {
            content: '> POKÉDEX_SCAN://';
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: var(--type-color);
            position: absolute;
            top: -1px;
            left: 12px;
            transform: translateY(-50%);
            background: #030509;
            padding: 0 6px;
            letter-spacing: 0.1em;
            opacity: 0.8;
        }

        .search-input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            font-family: 'Share Tech Mono', monospace;
            font-size: 16px;
            width: 100%;
            caret-color: var(--type-color);
        }

        .search-input::placeholder {
            color: rgba(255,255,255,0.2);
        }

        .search-btn {
            background: var(--type-color);
            border: none;
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #000;
            padding: 10px 20px;
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
        }

        .search-btn:hover {
            filter: brightness(1.2);
            transform: translateY(-1px);
        }

        /* ── QUICK BUTTONS ── */
        .quick-btn {
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 2px;
            padding: 4px 10px;
            cursor: pointer;
            background: transparent;
            transition: all 0.2s;
        }

        .quick-btn:hover {
            color: var(--type-color);
            border-color: var(--type-color);
            background: rgba(var(--type-color-rgb), 0.05);
        }

        /* ── CARD STYLES ── */
        .card-container {
            perspective: 1200px;
        }

        .tcg-card {
            transform-style: preserve-3d;
            transition: transform 0.08s ease-out;
            position: relative;
            width: 380px;
            height: 530px;
            border-radius: 20px;
            cursor: pointer;
        }

        /* Borda externa da carta — gradiente animado por tipo */
        .card-outer {
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 3px;
            background: conic-gradient(
                from 0deg,
                var(--type-color),
                #fff6,
                var(--type-color),
                #fff2,
                var(--type-color)
            );
            background-size: 300% 300%;
            animation: border-spin 4s linear infinite;
        }

        @keyframes border-spin {
            0%   { background-position: 0% 50%; filter: hue-rotate(0deg); }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; filter: hue-rotate(15deg); }
        }

        .card-inner {
            width: 100%;
            height: 100%;
            border-radius: 18px;
            background: linear-gradient(145deg, #1a1a2e 0%, #0f0f1a 100%);
            border: 2px solid rgba(255, 215, 0, 0.6);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Fundo da carta — gradiente radial por tipo */
        .card-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 20%, rgba(var(--type-color-rgb), 0.35) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 80%, rgba(var(--type-color-rgb), 0.2) 0%, transparent 50%),
                linear-gradient(160deg, #1c1c3a 0%, #0a0a14 100%);
        }

        /* Padrão geométrico de fundo */
        .card-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                repeating-linear-gradient(
                    45deg,
                    rgba(var(--type-color-rgb), 0.03) 0px,
                    rgba(var(--type-color-rgb), 0.03) 1px,
                    transparent 1px,
                    transparent 20px
                );
            opacity: 0.7;
        }

        /* Efeito holográfico prisma */
        .holo-prism {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: linear-gradient(
                110deg,
                transparent 20%,
                rgba(255, 100, 100, 0.07) 30%,
                rgba(255, 200, 50, 0.07) 40%,
                rgba(50, 255, 100, 0.07) 50%,
                rgba(50, 150, 255, 0.07) 60%,
                rgba(200, 50, 255, 0.07) 70%,
                transparent 80%
            );
            background-size: 200% 200%;
            animation: prism-shift 5s ease-in-out infinite alternate;
            mix-blend-mode: screen;
            pointer-events: none;
            z-index: 30;
        }

        @keyframes prism-shift {
            0%   { background-position: 0% 0%; opacity: 0.8; }
            50%  { opacity: 1.0; }
            100% { background-position: 100% 100%; opacity: 0.6; }
        }

        /* Reflexo brilhante (foil) */
        .holo-foil {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: linear-gradient(
                105deg,
                transparent 30%,
                rgba(255, 255, 255, 0.25) 47%,
                rgba(255, 255, 255, 0.45) 50%,
                rgba(255, 255, 255, 0.25) 53%,
                transparent 70%
            );
            background-size: 300% 300%;
            animation: foil-sweep 7s ease-in-out infinite alternate;
            mix-blend-mode: overlay;
            pointer-events: none;
            z-index: 31;
        }

        @keyframes foil-sweep {
            0%   { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Estrelas (sparkles) na carta */
        .sparkle {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #fff;
            animation: sparkle-anim 3s ease-in-out infinite;
            z-index: 32;
            pointer-events: none;
        }

        @keyframes sparkle-anim {
            0%, 100% { opacity: 0; transform: scale(0); }
            50%       { opacity: 1; transform: scale(1); box-shadow: 0 0 6px 2px rgba(255,255,255,0.8); }
        }

        /* ── CARD CONTENT ── */
        .card-content {
            position: relative;
            z-index: 10;
            padding: 10px 10px 8px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .pokemon-name {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 22px;
            color: #fff;
            text-shadow:
                0 0 10px var(--type-color),
                0 0 30px rgba(var(--type-color-rgb), 0.5),
                2px 2px 0px rgba(0,0,0,0.8);
            line-height: 1;
        }

        .pokemon-stage {
            font-family: 'Share Tech Mono', monospace;
            font-size: 8px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .hp-display {
            display: flex;
            align-items: center;
            gap: 4px;
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 22px;
            color: #ff4444;
            text-shadow: 0 0 10px rgba(255,68,68,0.6);
        }

        .hp-label {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
            align-self: flex-start;
            margin-top: 6px;
        }

        .type-badge {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--type-color);
            border: 2px solid rgba(255,255,255,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', monospace;
            font-size: 9px;
            font-weight: 700;
            color: #000;
            box-shadow: 0 0 10px var(--glow-color);
        }

        /* Área da imagem */
        .image-frame {
            width: 100%;
            height: 195px;
            border-radius: 10px;
            border: 3px solid rgba(255,215,0,0.5);
            overflow: hidden;
            position: relative;
            background:
                radial-gradient(ellipse at 50% 40%, rgba(var(--type-color-rgb), 0.3) 0%, transparent 70%),
                linear-gradient(180deg, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.3) 100%);
            box-shadow:
                inset 0 0 30px rgba(0,0,0,0.5),
                0 0 20px rgba(var(--type-color-rgb), 0.2);
            margin-bottom: 6px;
        }

        .image-scanlines {
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 3px,
                rgba(0,0,0,0.06) 3px,
                rgba(0,0,0,0.06) 4px
            );
            pointer-events: none;
            z-index: 5;
        }

        .image-corner {
            position: absolute;
            width: 14px;
            height: 14px;
            border-color: var(--type-color);
            border-style: solid;
            opacity: 0.8;
            z-index: 6;
        }
        .image-corner.tl { top: 4px; left: 4px; border-width: 2px 0 0 2px; }
        .image-corner.tr { top: 4px; right: 4px; border-width: 2px 2px 0 0; }
        .image-corner.bl { bottom: 4px; left: 4px; border-width: 0 0 2px 2px; }
        .image-corner.br { bottom: 4px; right: 4px; border-width: 0 2px 2px 0; }

        #pokeImage {
            width: 85%;
            height: 100%;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 0 20px var(--glow-color)) drop-shadow(0 10px 20px rgba(0,0,0,0.8));
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 4;
        }

        #pokeImage:hover {
            transform: scale(1.08) translateY(-4px);
            filter: drop-shadow(0 0 30px var(--glow-color)) drop-shadow(0 0 60px rgba(var(--type-color-rgb), 0.4)) drop-shadow(0 15px 30px rgba(0,0,0,0.8));
        }

        /* Faixa de info */
        .info-strip {
            background: linear-gradient(90deg, rgba(var(--type-color-rgb), 0.15), rgba(var(--type-color-rgb), 0.05), rgba(var(--type-color-rgb), 0.15));
            border-top: 1px solid rgba(var(--type-color-rgb), 0.3);
            border-bottom: 1px solid rgba(var(--type-color-rgb), 0.3);
            font-family: 'Share Tech Mono', monospace;
            font-size: 8px;
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 3px 8px;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
        }

        /* Ataques */
        .attack-block {
            padding: 5px 6px;
            border-radius: 6px;
            background: rgba(0,0,0,0.35);
            border: 1px solid rgba(var(--type-color-rgb), 0.15);
            margin-bottom: 4px;
            position: relative;
            overflow: hidden;
        }

        .attack-block::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--type-color);
        }

        .attack-name {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 11px;
            color: #fff;
            letter-spacing: 0.05em;
        }

        .attack-damage {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 18px;
            color: var(--type-color);
            text-shadow: 0 0 8px var(--glow-color);
        }

        .attack-desc {
            font-family: 'Rajdhani', sans-serif;
            font-size: 9px;
            color: rgba(255,255,255,0.45);
            line-height: 1.3;
            margin-top: 2px;
        }

        .energy-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--type-color);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 0 6px var(--glow-color);
            display: inline-block;
        }

        /* Barra inferior da carta */
        .card-stats-bar {
            display: flex;
            justify-content: space-between;
            padding: 4px 8px;
            background: rgba(0,0,0,0.4);
            border-top: 1px solid rgba(255,215,0,0.2);
            border-radius: 0 0 10px 10px;
            margin-top: 4px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 7px;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: block;
        }

        .stat-value {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,0.8);
        }

        /* Rodapé da carta */
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 4px 8px 2px;
        }

        .flavor-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 7.5px;
            font-style: italic;
            color: rgba(255,255,255,0.35);
            line-height: 1.4;
            max-width: 200px;
        }

        .card-number {
            font-family: 'Share Tech Mono', monospace;
            font-size: 9px;
            color: rgba(255,215,0,0.5);
            text-align: right;
        }

        /* ── UI ── */
        .ui-panel {
            font-family: 'Rajdhani', sans-serif;
        }

        .action-btn {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            border: 1px solid rgba(var(--type-color-rgb), 0.4);
            background: rgba(var(--type-color-rgb), 0.08);
            color: var(--type-color);
            padding: 10px 18px;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.2s;
            clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
            backdrop-filter: blur(10px);
        }

        .action-btn:hover {
            background: rgba(var(--type-color-rgb), 0.2);
            box-shadow: 0 0 15px var(--glow-color);
            transform: translateY(-2px);
        }

        .action-btn.shiny-active {
            background: rgba(255, 215, 0, 0.15);
            border-color: gold;
            color: gold;
            box-shadow: 0 0 15px rgba(255,215,0,0.4);
        }

        /* Scanline animado do título */
        @keyframes scan-line {
            0%   { top: -10%; }
            100% { top: 110%; }
        }

        .title-scan {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(var(--type-color-rgb), 0.8), transparent);
            animation: scan-line 3s linear infinite;
            pointer-events: none;
        }

        /* Fade-in da carta */
        @keyframes card-appear {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-container {
            animation: card-appear 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Type tags */
        .type-tag {
            font-family: 'Share Tech Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 2px;
            background: rgba(var(--type-color-rgb), 0.15);
            border: 1px solid rgba(var(--type-color-rgb), 0.4);
            color: var(--type-color);
        }

        /* Indicador de tipo na página */
        .type-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--type-color);
            box-shadow: 0 0 8px var(--glow-color);
            margin-right: 6px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.8); }
        }

        /* Status bar no topo */
        .status-bar {
            font-family: 'Share Tech Mono', monospace;
            font-size: 9px;
            color: rgba(255,255,255,0.25);
            letter-spacing: 0.1em;
        }

        .status-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #00ff88;
            box-shadow: 0 0 6px #00ff88;
            animation: pulse 1.5s ease-in-out infinite;
            margin-right: 6px;
        }

        /* Shiny toggle animation */
        @keyframes shiny-burst {
            0%   { transform: scale(0); opacity: 1; }
            100% { transform: scale(3); opacity: 0; }
        }

        .shiny-burst {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: radial-gradient(circle, rgba(255,215,0,0.8) 0%, transparent 70%);
            animation: shiny-burst 0.6s ease-out forwards;
            pointer-events: none;
            z-index: 50;
        }
    </style>
</head>

@php
    $typeColors = [
        'normal'   => ['hex' => '#A8A77A', 'rgb' => '168, 167, 122'],
        'fire'     => ['hex' => '#EE8130', 'rgb' => '238, 129, 48'],
        'water'    => ['hex' => '#6390F0', 'rgb' => '99, 144, 240'],
        'electric' => ['hex' => '#F7D02C', 'rgb' => '247, 208, 44'],
        'grass'    => ['hex' => '#7AC74C', 'rgb' => '122, 199, 76'],
        'ice'      => ['hex' => '#96D9D6', 'rgb' => '150, 217, 214'],
        'fighting' => ['hex' => '#C22E28', 'rgb' => '194, 46, 40'],
        'poison'   => ['hex' => '#A33EA1', 'rgb' => '163, 62, 161'],
        'ground'   => ['hex' => '#E2BF65', 'rgb' => '226, 191, 101'],
        'flying'   => ['hex' => '#A98FF3', 'rgb' => '169, 143, 243'],
        'psychic'  => ['hex' => '#F95587', 'rgb' => '249, 85, 135'],
        'bug'      => ['hex' => '#A6B91A', 'rgb' => '166, 185, 26'],
        'rock'     => ['hex' => '#B6A136', 'rgb' => '182, 161, 54'],
        'ghost'    => ['hex' => '#735797', 'rgb' => '115, 87, 151'],
        'dragon'   => ['hex' => '#6F35FC', 'rgb' => '111, 53, 252'],
        'dark'     => ['hex' => '#705746', 'rgb' => '112, 87, 70'],
        'steel'    => ['hex' => '#B7B7CE', 'rgb' => '183, 183, 206'],
        'fairy'    => ['hex' => '#D685AD', 'rgb' => '214, 133, 173'],
    ];

    $primaryType     = $pokemon['types'][0]['type']['name'] ?? 'normal';
    $secondaryType   = $pokemon['types'][1]['type']['name'] ?? null;
    $primaryColor    = $typeColors[$primaryType]['hex'] ?? '#A8A77A';
    $primaryColorRgb = $typeColors[$primaryType]['rgb'] ?? '168, 167, 122';

    $name   = ucfirst($pokemon['name'] ?? 'Missingno');
    $id     = str_pad($pokemon['id'] ?? 0, 3, '0', STR_PAD_LEFT);
    $weight = ($pokemon['weight'] ?? 0) / 10;
    $height = ($pokemon['height'] ?? 0) / 10;

    $imgDefault = $pokemon['sprites']['other']['official-artwork']['front_default'] ?? '';
    $imgShiny   = $pokemon['sprites']['other']['official-artwork']['front_shiny'] ?? $imgDefault;

    $hp = 50;
    foreach ($pokemon['stats'] ?? [] as $stat) {
        if ($stat['stat']['name'] === 'hp') $hp = $stat['base_stat'];
    }

    $moves = array_slice($pokemon['moves'] ?? [], 0, 2);

    // Usa os dados limpos enviados pelo Controller
    $genus     = $pokemon['species_data']['genus']       ?? 'Pokémon Desconhecido';
    $flavorText = $pokemon['species_data']['flavor_text'] ?? 'Um misterioso Pokémon cuja essência desafia a compreensão dos pesquisadores mais experientes.';

    // Tipo como abreviação para o badge
    $typeAbbr = strtoupper(substr($primaryType, 0, 2));
@endphp

<body style="--type-color: {{ $primaryColor }}; --type-color-rgb: {{ $primaryColorRgb }};">

    <!-- Fundo -->
    <div class="bg-grid"></div>
    <div class="bg-scan"></div>
    <div class="bg-vignette"></div>

    <!-- Partículas geradas por JS -->
    <div id="particles"></div>

    <!-- Layout principal -->
    <div class="relative z-10 min-h-screen flex flex-col items-center justify-start py-8 px-4 gap-8">

        <!-- ── STATUS BAR ── -->
        <div class="w-full max-w-2xl flex justify-between items-center status-bar">
            <span><span class="status-dot"></span>SISTEMA ONLINE</span>
            <span>POKÉDEX v3.1.0 // GEN I–IX</span>
            <span>POKÉMONS: 1025</span>
        </div>

        <!-- ── HEADER ── -->
        <div class="text-center relative">
            <div class="title-scan"></div>
            <h1 class="pokedex-header text-4xl font-black text-white tracking-widest" style="text-shadow: 0 0 20px var(--type-color), 0 0 60px rgba(var(--type-color-rgb), 0.4);">
                POKÉDEX TCG
            </h1>
            <div class="flex items-center justify-center gap-3 mt-2">
                <span class="type-indicator"></span>
                <span class="type-tag">{{ strtoupper($primaryType) }}</span>
                @if($secondaryType)
                    <span class="type-tag" style="background: rgba({{ $typeColors[$secondaryType]['rgb'] ?? '168,167,122' }}, 0.15); border-color: rgba({{ $typeColors[$secondaryType]['rgb'] ?? '168,167,122' }}, 0.4); color: {{ $typeColors[$secondaryType]['hex'] ?? '#A8A77A' }};">{{ strtoupper($secondaryType) }}</span>
                @endif
            </div>
        </div>
        <div class="header-line w-full max-w-2xl"></div>

        <!-- ── SEARCH TERMINAL ── -->
        <div class="w-full max-w-lg ui-panel">
            <form action="{{ route('pokemon.search') }}" method="GET" class="flex flex-col gap-3">
                <div class="search-terminal p-3 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--type-color); flex-shrink: 0;">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input
                        type="text"
                        name="query"
                        class="search-input"
                        placeholder="pikachu · charizard · 25 · 006 ..."
                        value="{{ request('query') }}"
                        autocomplete="off"
                        spellcheck="false"
                    >
                    <button type="submit" class="search-btn">SCAN</button>
                </div>

                <!-- Sugestões rápidas -->
                <div class="flex flex-wrap gap-2 px-1">
                    <span class="text-[10px] font-mono text-white/20 self-center">BUSCA RÁPIDA:</span>
                    @foreach(['pikachu', 'mewtwo', 'charizard', 'eevee', 'gengar'] as $suggestion)
                        <button type="button" class="quick-btn" onclick="document.querySelector('.search-input').value='{{ $suggestion }}'; this.closest('form').submit();">
                            {{ $suggestion }}
                        </button>
                    @endforeach
                </div>
            </form>
        </div>

        <!-- ── CARTA TCG ── -->
        <div class="card-container relative" id="cardContainer">
            <div class="tcg-card" id="tcgCard">

                <!-- Borda externa animada -->
                <div class="card-outer">
                    <div class="card-inner">
                        <div class="card-bg"></div>
                        <div class="card-pattern"></div>

                        <!-- Efeitos holográficos -->
                        <div class="holo-prism" id="holoPrism"></div>
                        <div class="holo-foil" id="holoFoil"></div>

                        <!-- Sparkles -->
                        <div class="sparkle" style="top: 15%; left: 20%; animation-delay: 0s;"></div>
                        <div class="sparkle" style="top: 25%; right: 18%; animation-delay: 1s;"></div>
                        <div class="sparkle" style="top: 60%; left: 12%; animation-delay: 2s;"></div>
                        <div class="sparkle" style="top: 70%; right: 15%; animation-delay: 0.5s;"></div>
                        <div class="sparkle" style="top: 45%; left: 45%; animation-delay: 1.5s;"></div>

                        <!-- Conteúdo -->
                        <div class="card-content">

                            <!-- Top bar: nome + HP -->
                            <div class="card-top-bar">
                                <div>
                                    <div class="pokemon-stage">◆ BÁSICO</div>
                                    <div class="pokemon-name">{{ $name }}</div>
                                </div>
                                <div class="hp-display">
                                    <span class="hp-label">PS</span>
                                    {{ $hp }}
                                    <div class="type-badge">{{ $typeAbbr }}</div>
                                </div>
                            </div>

                            <!-- Imagem -->
                            <div class="image-frame">
                                <div class="image-scanlines"></div>
                                <div class="image-corner tl"></div>
                                <div class="image-corner tr"></div>
                                <div class="image-corner bl"></div>
                                <div class="image-corner br"></div>
                                <img
                                    id="pokeImage"
                                    src="{{ $imgDefault }}"
                                    data-default="{{ $imgDefault }}"
                                    data-shiny="{{ $imgShiny }}"
                                    alt="{{ $name }}"
                                >
                            </div>

                            <!-- Faixa de dados -->
                            <div class="info-strip">
                                Nº {{ $id }} &nbsp;|&nbsp; {{ $genus }} &nbsp;|&nbsp; {{ $height }}m &nbsp;|&nbsp; {{ $weight }}kg
                            </div>

                            <!-- Ataques -->
                            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 4px; padding: 0 2px;">
                                @forelse($moves as $index => $moveData)
                                    @php
                                        $moveName      = ucwords(str_replace('-', ' ', $moveData['move']['name']));
                                        $dmg           = ($index === 0) ? '30+' : '60+';
                                        $energyCount   = $index + 1;
                                    @endphp
                                    <div class="attack-block">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <div style="display: flex; gap: -2px;">
                                                    @for($e = 0; $e < $energyCount; $e++)
                                                        <span class="energy-dot" style="margin-left: {{ $e > 0 ? '-4px' : '0' }};"></span>
                                                    @endfor
                                                </div>
                                                <span class="attack-name">{{ $moveName }}</span>
                                            </div>
                                            <span class="attack-damage">{{ $dmg }}</span>
                                        </div>
                                        @if($index === 0)
                                            <p class="attack-desc">Causa 30 de dano adicional para cada Energia do oponente ativo.</p>
                                        @endif
                                    </div>
                                @empty
                                    <div style="text-align:center; font-family: 'Share Tech Mono', monospace; font-size: 11px; color: rgba(255,255,255,0.3);">[ SEM DADOS DE ATAQUE ]</div>
                                @endforelse
                            </div>

                            <!-- Stats bar -->
                            <div class="card-stats-bar">
                                <div class="stat-item">
                                    <span class="stat-label">Fraqueza</span>
                                    <span class="stat-value">🔥 ×2</span>
                                </div>
                                <div class="stat-item" style="border-left: 1px solid rgba(255,255,255,0.08); border-right: 1px solid rgba(255,255,255,0.08); padding: 0 12px;">
                                    <span class="stat-label">Resistência</span>
                                    <span class="stat-value">— 30</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Recuo</span>
                                    <span class="stat-value">⭐⭐</span>
                                </div>
                            </div>

                            <!-- Rodapé -->
                            <div class="card-footer">
                                <p class="flavor-text">"{{ str_replace(["\f", "\n", "\r"], " ", $flavorText) }}"</p>
                                <div class="card-number">
                                    <div>Illus. 5ban Graphics</div>
                                    <div style="color: rgba(255,215,0,0.7); font-size: 11px;">{{ $id }}/1025 ★</div>
                                    <div style="font-size: 6px; color: rgba(255,255,255,0.15); margin-top: 2px;">©2024 Pokémon/Nintendo</div>
                                </div>
                            </div>

                        </div><!-- /card-content -->
                    </div><!-- /card-inner -->
                </div><!-- /card-outer -->

            </div><!-- /tcg-card -->
        </div><!-- /card-container -->

        <!-- ── BOTÕES ── -->
        <div class="flex gap-4 flex-wrap justify-center ui-panel">
            <button id="shinyToggle" class="action-btn">
                ✨ MODO SHINY
            </button>
            <a href="{{ route('pokemon.index') }}" class="action-btn" style="text-decoration: none; display: inline-flex; align-items: center;">
                ⟳ ALEATÓRIO
            </a>
        </div>

        <!-- Footer info -->
        <div class="status-bar text-center pb-4" style="font-size: 8px;">
            DADOS: POKEAPI.CO &nbsp;// &nbsp;POKÉMON TCG SIMULATOR &nbsp;// &nbsp;GERAÇÃO {{ ceil(($pokemon['id'] ?? 1) / 100) }}
        </div>

    </div>

    <script>
    // ── PARTÍCULAS ──
    (function() {
        const container = document.getElementById('particles');
        const colors = ['{{ $primaryColor }}'];
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.animationDuration = (8 + Math.random() * 12) + 's';
            p.style.animationDelay = (Math.random() * 10) + 's';
            p.style.opacity = 0.3 + Math.random() * 0.7;
            p.style.width = p.style.height = (1 + Math.random() * 3) + 'px';
            container.appendChild(p);
        }
    })();

    // ── TILT 3D ──
    const container = document.getElementById('cardContainer');
    const card      = document.getElementById('tcgCard');
    const foil      = document.getElementById('holoFoil');
    const prism     = document.getElementById('holoPrism');

    container.addEventListener('mousemove', (e) => {
        const rect   = container.getBoundingClientRect();
        const x      = e.clientX - rect.left;
        const y      = e.clientY - rect.top;
        const cx     = rect.width  / 2;
        const cy     = rect.height / 2;
        const rx     = ((y - cy) / cy) * -18;
        const ry     = ((x - cx) / cx) * 18;

        card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg) scale3d(1.04,1.04,1.04)`;

        const px = (x / rect.width)  * 100;
        const py = (y / rect.height) * 100;
        foil.style.cssText  += `; background-position: ${px}% ${py}%; animation: none;`;
        prism.style.cssText += `; background-position: ${px}% ${py}%; animation: none;`;
    });

    container.addEventListener('mouseleave', () => {
        card.style.transform = 'rotateX(0) rotateY(0) scale3d(1,1,1)';
        foil.style.animation  = 'foil-sweep 7s ease-in-out infinite alternate';
        prism.style.animation = 'prism-shift 5s ease-in-out infinite alternate';
    });

    // ── SHINY TOGGLE ──
    const shinyBtn = document.getElementById('shinyToggle');
    const pokeImg  = document.getElementById('pokeImage');
    let isShiny = false;

    shinyBtn.addEventListener('click', () => {
        isShiny = !isShiny;

        // Burst effect
        const burst = document.createElement('div');
        burst.className = 'shiny-burst';
        card.appendChild(burst);
        setTimeout(() => burst.remove(), 700);

        if (isShiny) {
            pokeImg.style.opacity = '0';
            setTimeout(() => {
                pokeImg.src = pokeImg.dataset.shiny;
                pokeImg.style.opacity = '1';
            }, 200);
            shinyBtn.textContent = '⬅ MODO NORMAL';
            shinyBtn.classList.add('shiny-active');
        } else {
            pokeImg.style.opacity = '0';
            setTimeout(() => {
                pokeImg.src = pokeImg.dataset.default;
                pokeImg.style.opacity = '1';
            }, 200);
            shinyBtn.textContent = '✨ MODO SHINY';
            shinyBtn.classList.remove('shiny-active');
        }
    });

    pokeImg.style.transition = 'opacity 0.3s ease, transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease';
    </script>

</body>
</html>
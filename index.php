<?php


if (!defined('BASE_URL')) {
    define('BASE_URL', '/statpoker/S/public');
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>StatPoker</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/assets/icons/coin.ico"/>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/main.css">
</head>

<body class="page">
    <header class="header">
        <a class="nav__home">
            <img class="logo" src="<?= BASE_URL ?>/assets/imgs/poker.png">
            <div class="sidebar__title">
            Stat<br>
            <span class="red-span">Poker</span>
        </div>
        </a>

        <nav class="nav">
            <a class="nav__button" >Quem Somos</a>
            <a class="nav__button" >Sobre Nós</a>
            <a class="nav__button" >Planos</a>
            <a class="nav__button" >Fale Conosco</a>
        </nav>

        <a class="nav__button nav__button-login" href="/statpoker/S/src/Views/home/home.php">Entrar</a>
    </header>

    <main class="main">
        <div class="poker-chip-bg">
            <svg class="poker-chip-svg" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <!-- Edge Insert set to fill red -->
                <g id="edge-insert">
                <path class="chip-path path-dark fill-dark" d="
                    M 314.4 12 
                    A 341.5 341.5 0 0 1 385.6 12
                    L 378.2 81.3 
                    A 270 270 0 0 0 321.8 81.3 
                    Z" 
                />
                </g>
            </defs>

            <g>
                <!-- 1. Outer Disc (Red) -->
                <circle class="chip-path path-red fill-red" cx="350" cy="350" r="340" />

                <!-- 2. Middle Ring Area (White) - Stacked above outer disc -->
                <circle class="chip-path path-white fill-white" cx="350" cy="350" r="270" />

                <!-- 3. Inner Center Circle (Red) - Stacked above middle ring -->
                <circle class="chip-path path-red fill-red" cx="350" cy="350" r="180" />

                <!-- 4. 8 Edge Inserts (Red) - Stacked above white middle ring -->
                <use href="#edge-insert" />
                <use href="#edge-insert" transform="rotate(45 350 350)" />
                <use href="#edge-insert" transform="rotate(90 350 350)" />
                <use href="#edge-insert" transform="rotate(135 350 350)" />
                <use href="#edge-insert" transform="rotate(180 350 350)" />
                <use href="#edge-insert" transform="rotate(225 350 350)" />
                <use href="#edge-insert" transform="rotate(270 350 350)" />
                <use href="#edge-insert" transform="rotate(315 350 350)" />
            </g>
            </svg>
        </div>

        <div class="poker-chip-bg2">
            <svg class="poker-chip-svg" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <!-- Edge Insert set to fill red -->
                <g id="edge-insert-2">
                <path class="chip-path2 path-white fill-white" d="
                    M 314.4 12 
                    A 341.5 341.5 0 0 1 385.6 12
                    L 378.2 81.3 
                    A 270 270 0 0 0 321.8 81.3 
                    Z" 
                />
                </g>
            </defs>

            <g>
                <!-- 1. Outer Disc (Red) -->
                <circle class="chip-path2 path-dark fill-dark" cx="350" cy="350" r="340" />

                <!-- 2. Middle Ring Area (White) - Stacked above outer disc -->
                <circle class="chip-path2 path-white fill-white" cx="350" cy="350" r="270" />

                <!-- 3. Inner Center Circle (Red) - Stacked above middle ring -->
                <circle class="chip-path2 path-dark fill-dark" cx="350" cy="350" r="180" />

                <!-- 4. 8 Edge Inserts (Red) - Stacked above white middle ring -->
                <use href="#edge-insert-2" />
                <use href="#edge-insert-2" transform="rotate(45 350 350)" />
                <use href="#edge-insert-2" transform="rotate(90 350 350)" />
                <use href="#edge-insert-2" transform="rotate(135 350 350)" />
                <use href="#edge-insert-2" transform="rotate(180 350 350)" />
                <use href="#edge-insert-2" transform="rotate(225 350 350)" />
                <use href="#edge-insert-2" transform="rotate(270 350 350)" />
                <use href="#edge-insert-2" transform="rotate(315 350 350)" />
            </g>
            </svg>
        </div>

        <section class="landing">
            <div class="landing__display">
                <p class="landing__display-kicker">
                    ♠ <span class="red-span">♥</span> ♣ <span class="red-span">♦</span> — gestão de clubes de poker
                </p>
                <h1 class="landing__display-title"> STAT<br><span class="landing__display-title_span red-span">POKER</span></h1>
                <p class="landing__display-paragraph">
                    Seu clube inteiro, numa só mesa. Torneios, 
                    cash game, bar, financeiro, ranking e o 
                    app do jogador — <b>100% online</b>, com 
                    banco exclusivo e 2FA.
                </p>
            </div>
        </section>
  

    </main>

    <script src="./scripts/main.js"></script>
</body>

</html>

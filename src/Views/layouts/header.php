<?php
//Captura do caminho atual da URL para destacar a sessão ativa na sidebar
$uriAtual = $_SERVER['REQUEST_URI'] ?? '';

//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Titulo dinamico da aba do navegador-->
    <title><?= $titulo ?? 'StatPoker' ?></title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/public/assets/icons/coin.ico"/>
    <!--Importação do CSS central-->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/main.css">
</head>
<body class="page">
  <div class="app-layout">
    <!--Barra de navegação lateral-->
    <aside class="sidebar">
      <a href="<?= BASE_URL ?>" class="sidebar__home">
        <img class="sidebar__logo" src="<?= BASE_URL ?>/public/assets/imgs/poker.png" alt="StatPoker Logo">
        <div class="sidebar__title">
            Stat<br>
            <span class="red-span">Poker</span>
        </div>
      </a>

      <nav class="vertical__nav">
        <ul class="vertical__nav-ul">
          <li class="vertical__nav-subtitle"><span class="red-span">♠</span> Operação</li>
          <!--Exemplo de item com verificação dinâmica para estado 'active'-->
          <li><a href="<?= BASE_URL ?>/src/Views/home/home.php" 
            class="vertical__nav-link <?= str_contains($uriAtual,  'home.php') ? 'active' : '' ?>">Home</a></li>
          <li><a href="<?= BASE_URL ?>/src/Views/torneios/lista.php" 
            class="vertical__nav-link <?= str_contains($uriAtual, '/torneios/') ? 'active' : '' ?>">Torneios</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Cash Game</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Bar & Restaurante</a></li>

          <li class="vertical__nav-subtitle"><span class="red-span">♥</span> Cadastros</li>
          <li><a href="#" 
            class="vertical__nav-link">Jogadores</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Funcionários</a></li>

          <li class="vertical__nav-subtitle"><span class="red-span">♣</span> Engajamento</li>
          <li><a href="#" 
            class="vertical__nav-link">Ranking</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Tickets</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Jackpots</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Marketing</a></li>

          <li class="vertical__nav-subtitle"><span class="red-span">♦</span> Gestão</li>
          <li><a href="#" 
            class="vertical__nav-link">Financeiro</a></li>
          <li><a href="#" 
            class="vertical__nav-link">Relatórios</a></li>
        </ul>
      </nav>

      <p class="sidebar__footer">
        v0.3 - Protótipo
      </p>
    </aside>

    <div class="app-main-wrapper">
    <!--Barra superior-->
    <header class="user__header">
      <div class="header__title">
        <h2><?= $tituloPagina  ?></h2>
      </div>

      <div class="user__header__user">
        <div class="user__header__user-info">
          <span class="user__header__user-name">Usuário</span>
          <span class="user__header__user-level">Administrador</span>
        </div>
        <img class="user__header__user-avatar" src="<?= BASE_URL ?>/public/assets/imgs/user.png" alt="Avatar">

        <div class="user__header__popup">
          <button class="popup-btn">Perfil</button>
          <button onclick="window.location.href='../../../index.php'" class="popup-btn">Sair</button>
        </div>
      </div>
    </header>

    <!--Abertura da area rea do conteudo princpal-->
    <main class="main-content">
      <div class="view__header">
        <div>
          <!--Titulo e subtitulo dinamico da pagina-->
          <h1 class="view__header__title"><?= $tituloConteudo ?></h1>
          <p><?= $subtituloConteudo ?></p>
        </div>
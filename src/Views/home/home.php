<?php

$titulo = "Home - StatPoker";
$tituloPagina = "Home";
$tituloConteudo = "Painel Principal";
$subtituloConteudo = "Visão geral sobre o sistema";

//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');

//Importação de dependências necessarias
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../DAO/TorneioDAO.php';
require_once __DIR__ . '/../../DAO/ItemDAO.php';

//Inicializa a conexão com o banco e a camada DAO
$pdo = Database::getConexao();
$tDAO = new TorneioDAO($pdo);
$iDAO = new ItemDAO($pdo);

//Busca todos torneios salvos no banco com data igual a atual
$torneios = $tDAO->listarTorneiosHoje();

//Importa navbar
require_once __DIR__ . '/../layouts/header.php'; ?>
</div>

<div>
    <section>
        <h3>Torneios de Hoje</h3>

        <?php if (empty($torneios)) : ?>
            <div>
                <p>Nenhum torneio agendado para hoje</p>
            </div>
        <?php else : ?>
            <div class="home__cards-grid">
                <?php foreach ($torneios as $t) : ?>
                    <?php
                        $itens = $iDAO->buscarPorTorneio($t->getId());

                        //Inicialização de variavel para futura formatação
                        $buyIn = 0;

                        foreach ($itens as $i) {
                            $nomeItem = strtolower(trim($i->getItem()));

                            if ($nomeItem === 'buy-in' || $nomeItem === 'buy in') {
                                $buyIn = (float)$i->getValor();
                                break;
                            }
                        }
                        //Formatações de valores monetarios
                        $buyInF = ($buyIn > 0) ? 'R$ ' . number_format($buyIn, 0, ',', '.') : '-';
                        $dataF = date('d/m - H:i', strtotime($t->getData() . ' ' . $t->getHorario()));
                        $premiacaoF = 'R$ ' . number_format($t->getGarantida() ?? 0, 2, ',', '.');
                        //Função com retorno de array da badge de status
                        $infoStatus = $t->getStatus();    
                    ?>

                    <div class="home__tournament-card">
                            <div class="home__tournament-card__header">
                                <span class="badge <?= $infoStatus['badgeClass'] ?>">
                                    <?= $infoStatus['label'] ?>
                                </span>
                                <span class="home__tournament-card__time"><?= date('H:i', strtotime($t->getHorario())) ?>h</span>
                            </div>

                            <div class="home__tournament-card__body">
                                <h4 class="home__tournament-card__title"><?= htmlspecialchars($t->getNome()) ?></h4>
                                <div class="home__tournament-card__format">
                                    <p class="home__tournament-card__detail">Formato: <strong><?= htmlspecialchars($t->getFormato()) ?></strong></p>
                                    <p class="home__tournament-card__detail">Blinds: <strong><?= htmlspecialchars($t->getBlinds()) ?></strong></p>
                                </div>
                                <div class="home__tournament-card__financials">
                                    <div class="home__tournaments-card__financials-row"><h3>Buy-in:</h3> <span><?= $buyInF ?></span></div>
                                    <div class="home__tournaments-card__financials-row"><h3>Garantido:</h3> <span><?= $premiacaoF ?></span></div>
                                </div>
                            </div>

                            
                    </div>
                <?php endforeach ; ?>
            </div>
        <?php endif ; ?>
    </section>
</div>
    


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
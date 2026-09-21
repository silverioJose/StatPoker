<?php 

//Definição das variaveis de exibição dinamica
$titulo = "Torneios - StatPoker";
$tituloPagina = "Torneios";
$tituloConteudo = "Gestão de Torneios";
$subtituloConteudo = "Criação, caixa, salão e resultados";

//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');

//Caminho para importação do script
$script = BASE_URL . "/public/js/torneio-filtro.js";

//Importação de dependências necessarias
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../DAO/TorneioDAO.php';
require_once __DIR__ . '/../../DAO/ItemDAO.php';

//Inicializa a conexão com o banco e a camada DAO
$pdo = Database::getConexao();
$tDAO = new TorneioDAO($pdo);
$iDAO = new ItemDAO($pdo);

//Busca todos torneios salvos no banco
$torneios = $tDAO->listarTodos();

//Importação do header
require_once __DIR__ . '/../layouts/header.php'; ?>

    <a class="view__btn" href="<?=BASE_URL?>/src/Views/torneios/criar.php">+ Novo Torneio</a>
<!--Fechamento da div aberta no header-->
</div>

<div class="filters-bar">
    <!--Pesquisa por texto-->
    <input type="text" id="input-filtro" placeholder="Buscar" class="filters-bar__input">
    <!--Filtro por status-->
    <select id="select-status" class="filters-bar__select">
        <option value="">Todos os Status</option>
        <option value="agendado">Agendado</option>
        <option value="inscrições abertas">Inscrições</option>
        <option value="ao vivo">Ao vivo</option>
        <option value="encerrado">Encerrado</option>
    </select>
</div>

<!--Cabeçalho da tabela de torneios-->
<table class="view__tournaments__table">
    <thead>
        <tr class="view__tournaments__table_rows">
            <th>TORNEIO</th>
            <th>DATA / HORA</th>
            <th>STATUS</th>
            <th>BUY-IN</th>
            <th>GARANTIDO</th>
            <th>AÇÕES</th>
        </tr>
    </thead>

    <!--Corpo da tabela, preenchido pelo PHP-->
    <tbody class="view__tournaments__table_data">
        <?php if (empty($torneios)) : ?>
            <tr><td class="no_tournaments" colspan="6">Nenhum torneio cadastrado.</td></tr>
        <?php else : ?>
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
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($t->getNome()) ?></strong>
                        <br>
                        <small class="tournament-info__extra">
                            <?= $t->isRanking() ? ' • ranking ativo' : '' ?>
                            <?= $t->isJackpot() ? ' • jackpot' : '' ?>
                        </small>
                    </td>
                    <td><?= $dataF ?></td>
                    <td>
                        <span class="badge <?= $infoStatus['badgeClass'] ?>">
                            <?= $infoStatus['label'] ?>
                        </span>
                    </td>
                    <td><?= $buyInF ?></td>
                    <td><?= $premiacaoF ?></td>
                    <td class="action__btn">
                        <!--Botão de edição, passa o ID via parametro GET na URL para criar.php-->
                        <a href="<?= BASE_URL ?>/src/Views/torneios/criar.php?id=<?= $t->getId() ?>" class="btn-sm">Editar</a>
                        <!--Botão de exclusao com confirmação, passa ID via GET na URL para excluir.php-->
                        <a href="<?= BASE_URL ?>/src/Controller/excluir.php?id=<?= $t->getId() ?>" class="btn-sm btn-sm_excluir" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach ; ?>
        <?php endif ; ?>
    </tbody>
</table>
<!--Importação do footer-->
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php 

//Definição das variaveis de exibição dinamica
$titulo = "Torneios - StatPoker";
$tituloPagina = "Torneios";
$tituloConteudo = "Criação de Torneios";
$subtituloConteudo = "Use estruturas pré-configuradas ou personalize tudo";

//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');

//Caminho para importação do script
$script = BASE_URL . "/public/js/torneio-novo.js";

//Importação de dependências necessarias
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../DAO/TorneioDAO.php';
require_once __DIR__ . '/../../DAO/ItemDAO.php';

//Inicializa a conexão com o banco e a camada DAO
$pdo = Database::getConexao();
$tDAO = new TorneioDAO($pdo);
$iDAO = new ItemDAO($pdo);

//Pega valor do ID via GET, caso acessado por meio do botão de edição
$id = $_GET['id'] ?? null;
$t = null;

if ($id) {
    $t = $tDAO->buscarPorId((int)$id);
}

//Definicao dos itens de caixa padrao
$itensArray = [
    ['item' => 'Buy-in', 'valor' => 250, 'fichas' => 30000, 'taxa' => 30, 'limite' => 1],
    ['item' => 'Re-entry', 'valor' => 250, 'fichas' => 30000, 'taxa' => 30, 'limite' => 2],
    ['item' => 'Add-on', 'valor' => 100, 'fichas' => 20000, 'taxa' => 0, 'limite' => 1]
];

//Se for edição, substitui pelos itens gravados no banco
if ($t) {
    //Busca todas linha do banco com  chave estrangeira igual ao id do torneio em edição
    $itensBanco = $iDAO->buscarPorTorneio($t->getId());

    //Checa se há dados
    if (!empty($itensBanco)) {
        //Sobreescrita do array padrao
        $itensArray = [];

        foreach ($itensBanco as $itemObj) {
            //Mapeamento de objeto para array
            $itensArray[] = [
                'item' => $itemObj->getItem(),
                'valor' => $itemObj->getValor(),
                'fichas' => $itemObj->getFichas(),
                'taxa' => $itemObj->getTaxaAdm(),
                'limite' => $itemObj->getLimiteJogador()
            ];
        }
    }
}

//Convercao do array associativo em string json
$itensCaixaJson = json_encode($itensArray);

//Importa navbar
require_once __DIR__ . '/../layouts/header.php'; ?>

    <a class="view__btn" href="<?=BASE_URL?>/src/Views/torneios/lista.php">Voltar</a>
</div>

<div class="form-container">

    <form action="<?= BASE_URL ?>/src/Controller/salvar.php" method="POST" id="form-torneio">
        
        <?php if ($t): ?>
            <input type="hidden" name="id" value="<?=$t->getId()?>">
        <?php endif; ?>
        
        <!--Uso de htmlspecialchars para caracteres reservados do html em entidades seguras-->
        <input type="hidden" name="itens_caixa" id="input-itens-caixa" value="<?= htmlspecialchars($itensCaixaJson, ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-grid">

            <!--Coluna esquerda-->
            <div class="form-column">
                
                <div class="fieldset-wrapper">
                    <fieldset class="card">
                        <legend>Dados Gerais</legend>

                        <div>
                            <label>Nome do Torneio</label>
                            <input type="text" name="nome" placeholder="Ex.: Main Event Sabado" value="<?= $t ? htmlspecialchars($t->getNome(), ENT_QUOTES, 'UTF-8') : '' ?>" required>
                        </div>

                        <div class="form-row">
                            <div>
                                <label>Data</label>
                                <input type="date" name="data" value="<?= $t ? $t->getData() : date('Y-m-d') ?>" required>
                            </div>

                            <div>
                                <label>Horário</label>
                                <input type="time" name="horario" value="<?= $t ? $t->getHorario() : '20:00' ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div>
                                <label>Formato</label>
                                <select name="formato" id="t-formato">
                                    <option value="Um dia" <?= ($t && $t->getFormato() === 'Um dia') ? 'selected' : ''?> >
                                        Um dia</option>
                                    <option value="Classificatório com dia final" <?= ($t && $t->getFormato() === 'Classificatório com dia final') ? 'selected' : ''?> >
                                        Classificatório com dia final</option>
                                </select>
                            </div>

                            <div>
                                <label>Premiação Garantida</label>
                                <input type="number" step="250" name="garantida" placeholder="10.000" value="<?= $t ? $t->getGarantida() : '' ?>">
                            </div>
                        </div>

                        <div id="campo-etapas" style="display:none;">
                            <label>Etapas classificatórias (dias 1A, 1B...)</label>
                            <select name="etapas">
                                <option value="2 etapas + dia final" <?= ($t && $t->getEtapas() === '2 etapas + dia final') ? 'selected' : ''?> >
                                    2 etapas + dia final</option>
                                <option value="3 etapas + dia final" <?= ($t && $t->getEtapas() === '3 etapas + dia final') ? 'selected' : ''?> >
                                    3 etapas + dia final</option>
                                <option value="4 etapas + dia final" <?= ($t && $t->getEtapas() === '4 etapas + dia final') ? 'selected' : ''?> >
                                    4 etapas + dia final</option>
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div class="fieldset-wrapper">
                    <fieldset class="card">
                        <legend>Itens de caixa</legend>
                        <table class="table-caixa">
                            <thead>
                                <tr>
                                    <th>ITEM</th>
                                    <th>VALOR</th>
                                    <th>FICHAS</th>
                                    <th>TAXA ADM.</th>
                                    <th>LIMITE</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody id="tbody-itens-caixa">
                                <!--Preenchido peolo js-->
                            </tbody>
                        </table>
                        <button class="btn-sm" type="button" id="t-add-item">＋ Adicionar item</button>
                    </fieldset>
                </div>
            </div>

            <!--Coluna direita-->
            <div class="form-column">
                <div class="fieldset-wrapper">
                    <fieldset class="card">
                        <legend>Estrutura de Blinds</legend>
                        <label>Estrutura pré-cadastrada</label>
                        <select name="blinds" id="t-blinds">
                            <option value="padrao" <?= ($t && $t->getBlinds() === 'padrao') ? 'selected' : '' ?> >
                                Padrão 20 min - late reg nível 8</option>
                            <option value="turbo" <?= ($t && $t->getBlinds() === 'turbo') ? 'selected' : '' ?> >
                                Padrão 12 min - late reg nível 10</option>
                            <option value="deep" <?= ($t && $t->getBlinds() === 'deep') ? 'selected' : '' ?> >
                                Padrão 30 min - late reg nível 6</option>
                        </select>

                        <div class="blinds-table-container">
                            <table class="table-blinds">
                                <thead>
                                    <tr>
                                        <th>NÍVEL</th>
                                        <th>SB/BB</th>
                                        <th>ANTE</th>
                                        <th>TEMPO</th>
                                    </tr>
                                </thead>

                                <tbody id="t-blinds-tbody">
                                    <!--Preview de blinds via js-->
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <div class="fieldset-wrapper">
                    <fieldset class="card">
                        <legend>Premição & Ranking</legend>
                        <label>Estrutura de premiação</label>
                        <select name="premiacao">
                            <option value="Automática - 12% do field premiado" <?= ($t && $t->getPremiacao() === 'Automática - 12% do field premiado') ? 'selected' : '' ?> >
                                Automática — 12% do field premiado</option>
                            <option value="Percentuais fixos (50/30/20)" <?= ($t && $t->getPremiacao() === 'Percentuais fixos (50/30/20)') ? 'selected' : '' ?> >
                                Percentuais fixos (50/30/20)</option>
                            <option value="Personalizada..." <?= ($t && $t->getPremiacao() === 'Personalizada...') ? 'selected' : '' ?> >
                                Personalizada...</option>
                        </select>

                        <div class="toggle-group">
                            <label class="switch">
                                <input type="checkbox" name="ranking" value="1" <?= ($t && $t->isRanking()) ? 'checked' : '' ?> >
                                <span>Pontua no ranking da temporada</span>
                            </label>

                            <label class="switch">
                                <input type="checkbox" name="jackpot" value="1" <?= ($t && $t->isJackpot()) ? 'checked' : '' ?>>
                                <span>Contribui para o jackpot</span>
                            </label>
                        </div>
                    </fieldset>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="<?= BASE_URL ?>/src/Views/torneios/lista.php" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="view__btn">Salvar Torneio</button>
        </div>

    </form>

</div>

<!--Importação do footer-->
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
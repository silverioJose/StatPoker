<?php
/**
 * Controller responsavel pela Inserção/Atualização de torneios
 * Recebe ID do torneio, valida e executa modificações via TorneioDAO
 */
//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');

//Importação de dependências necessarias
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Model/Torneio.php';
require_once __DIR__ . '/../Model/ItemCaixa.php';
require_once __DIR__ . '/../DAO/TorneioDAO.php';
require_once __DIR__ . '/../DAO/ItemDAO.php';

//Inicializa a conexão com o banco e a camada DAO
$pdo = Database::getConexao();
$tDAO = new TorneioDAO($pdo);
$iDAO = new ItemDAO($pdo);

$t = new Torneio();

//Garante que codigo só rode se dados tiverem sido enviados por form via post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/src/Views/torneios/lista.php');
    exit;
}

//Verificação de id, verifica se campo $_POST['id'] existe e não esta vazio
if (!empty($_POST['id'])) {
    //Converte string id em int e grava valor dentro do objeto
    $t->setId((int)$_POST['id']);
}

//Atribuição com fallback seguro
$t->setNome(trim($_POST['nome'] ?? ''));
$t->setData(!empty($_POST['data']) ? $_POST['data'] : null);
$t->setHorario(!empty($_POST['horario']) ? $_POST['horario'] : null);

//Etapas apenas se for classificatório
$formato = $_POST['formato'] ?? 'Um dia';
$t->setFormato($formato);
$t->setEtapas($formato === 'Classificatório com dia final' ? ($_POST['etapas'] ?? null) : null);

//Casting de valores numéricos e booleanos
$t->setGarantida(!empty($_POST['garantida']) ? (float)$_POST['garantida'] : null);
$t->setBlinds($_POST['blinds'] ?? 'padrao');
$t->setPremiacao($_POST['premiacao'] ?? null);

//Booleans retornam true se o checkbox estiver marcado
$t->setRanking(isset($_POST['ranking']) && $_POST['ranking'] === '1');
$t->setJackpot(isset($_POST['jackpot']) && $_POST['jackpot'] === '1');

//Executa o salvamento do torneio no banco de dados
$tDAO->salvar($t);

//Retorna ID do objeto $t
$torneioId = $t->getId();

//Gravacao itens de caixa
if (!empty($_POST['itens_caixa'])) {
    
    $jsonLimpo = html_entity_decode($_POST['itens_caixa']);
    $itensArray = json_decode($jsonLimpo, true);

    if (is_array($itensArray)) {
        //Limpa ietns caixa para evitar duplicação
        $iDAO->deletarPorTorneio($torneioId);

        foreach ($itensArray as $dado) {
            
            $item = new ItemCaixa();
            $item->setTorneioId($torneioId);
            $item->setItem($dado['item'] ?? '');
            $item->setValor((float)($dado['valor'] ?? 0));
            
            //Garantia: se for vazio/null, vira 0 (evita o erro NOT NULL no MySQL)
            $item->setFichas((int)($dado['fichas'] ?? 0));
            
            $item->setTaxaAdm(isset($dado['taxa']) ? (float)$dado['taxa'] : 0.0);
            $item->setLimiteJogador(isset($dado['limite']) ? (int)$dado['limite'] : 0);

            //Executa o salvamento dos itens de caixa no banco de dados
            $iDAO->salvar($item);
        }
    }
}

//Redireciona de volta para a lista
header('Location: ' . BASE_URL . '/src/Views/torneios/lista.php');
exit;
?>
<?php
/**
 * Controller responsavel pela exclusão de torneios
 * Recebe ID do torneio, valida e executa remoção via TorneioDAO
 */
//Apontamento até a pasta raiz
if (!defined('BASE_URL')) define('BASE_URL', '/statpoker/S');

//Importação de dependências necessarias
require_once __DIR__ . '/../../src/Config/Database.php';
require_once __DIR__ . '/../../src/DAO/TorneioDAO.php';

//Captura ID vindo de requisição GET ou POST
$id = $_GET['id'] ?? $_POST['id'] ?? null;

//Garante que ID exista e seja uma número inteiro valido
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header('Location: ' . BASE_URL . '/src/Views/torneios/lista.php');
    exit;
}
//Inicializa a conexão com o banco e a camada DAO
$pdo = Database::getConexao();
$dao = new TorneioDAO($pdo);

//Executa a exclusão no banco de dados
$dao->excluir((int)$id);

//Redirecionamento para pagina inical de torneios
header('Location: ' .  BASE_URL  . '/src/Views/torneios/lista.php');
exit;
?>
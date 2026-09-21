<?php
/**
 * Classe responsavel pelo gerenciamento da conexão com o banco
 * 
 * Implementa o padrão Singleton para garantir que apenas uma
 * instância da conexão PDO seja criada durante a execução da aplicação
 */
class Database {
  //Endereço do servidor do banco de dados
  private static string $host = "localhost";

  //Usuario para autentificação no banco
  private static string $user = "root";

  //Senha para autentificação no banco
  private static string $pass = "";

  //Nome do banco de dados
  private static string $db = "StatPoker";

  //Conjunto de caracteres da conexão
  private static string $charset = 'utf8mb4';

  //Guarda unica instancia do objeto PDO
  private static ?PDO $instance = null;

  /**
   * Retorna a instancia unica da conexao PDO
   * 
   * Caso a conexão ainda não exista, ela será inicializada
   * com as configurações definidas na classe
   * 
   * Return self::$instance - Retona objeto de conexão PDO ativo
   * 
   * Throws PDOException - Se a conexão com banco falhar
   */
  public static function getConexao() : PDO {
    if(self::$instance===null) {

      //Data source name, string que contem informações necessárias 
      //para conectar php a um banco usando extensão pdo
      $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset . ";";

      //PDO config options
      $options = [
        //Lança exceções em erros de SQL
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 

        //Retorna dados como arrays associativos
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 

        //Desativa emulação para uso de prepared statements reais
        PDO::ATTR_EMULATE_PREPARES => false 
      ];

      try {
        //Inicializa conexão com banco
        self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
      } catch (PDOException $e) {
        throw new PDOException("Conexão com banco de dados falhou: " . $e->getMessage(), (int)$e->getCode());
      }
    }
    return self::$instance;
  }
}
?>
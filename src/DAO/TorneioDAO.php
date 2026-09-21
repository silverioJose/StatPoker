<?php

require_once __DIR__ . '/../Model/Torneio.php';
/**
 * Classe Data Access Object para entidade Torneio
 * Responsavel por executar todas as operações de CRUD no banco
 */
class TorneioDAO {
    private PDO $pdo;
    //Recebe e guarda a conexão com banco, assim permitindo que 
    //todos metodos da classe reutilizarem a mesma conexao PDO
    public function __construct($pdo) {
        //Pega conexao do arquivo de conexao e guarda na variavel local
        $this->pdo = $pdo;
    }

    public function listarTodos(): array {
        $sql = "SELECT * FROM torneios ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $linhas = $stmt->fetchAll();
        $torneios = [];
        
        foreach($linhas as $linha) {
            $torneios[] = $this->mapearObjeto($linha);
        }
        return $torneios;
    }


    public function listarTorneiosHoje(): array {
        $hoje = date('Y-m-d'); //Data atual

        $sql = "SELECT * FROM torneios WHERE data = :hoje ORDER BY horario ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':hoje', $hoje);
        $stmt->execute();

        $linhas = $stmt->fetchAll();
        $torneios = [];

        foreach ($linhas as $linha) {
            $torneios[] = $this->mapearObjeto($linha);
        }
        return $torneios;
    }

    public function buscarPorId(int $id): ?Torneio {
        $sql = "SELECT * FROM torneios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        //Substitui :id pelo valor de $id, garantindo que seja tratado como inteiro
        $stmt->execute();

        $linha = $stmt->fetch();

        //Caso torneio não exista
        if (!$linha) {
            return null;
        }

        return $this->mapearObjeto($linha);
    }

    //Decide e executa a operação (INSERT ou UPDATE) com base no ID
    public function salvar(Torneio $torneio): bool {
        if ($torneio->getId() === null) {
            return $this->inserir($torneio);
        } else {
            return $this->atualizar($torneio);
        }
    }

    private function inserir(Torneio $torneio): bool {
        $sql = "INSERT INTO torneios (
            nome, data, horario, formato, etapas, 
            garantida, blinds, premiacao, ranking, jackpot
        ) VALUES (
            :nome, :data, :horario, :formato, :etapas, 
            :garantida, :blinds, :premiacao, :ranking, :jackpot
        )";

        $stmt = $this->pdo->prepare($sql);
        $sucesso = $stmt->execute($this->getParametros($torneio));

        if ($sucesso) {
            //Vincula o ID gerado pelo auto-increment ao objeto na memória
            $torneio->setId((int) $this->pdo->lastInsertId());
        }

        return $sucesso;
    }

    private function atualizar(Torneio $torneio): bool {
        $sql = "UPDATE torneios SET 
            nome = :nome, data = :data, horario = :horario, 
            formato = :formato, etapas = :etapas, garantida = :garantida, 
            blinds = :blinds, premiacao = :premiacao, ranking = :ranking, 
            jackpot = :jackpot
            WHERE id = :id";

        $params = $this->getParametros($torneio);
        //Busca ID separadamente porque somente atualizar faz uso dele
        $params[':id'] = $torneio->getId();

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    //Remove um registro pelo ID
    public function excluir($id): bool {
        $sql = "DELETE FROM torneios WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id
        ]);
    }

    //Preenche array com dados de uma instancia torneio
    private function getParametros(Torneio $torneio): array {

        return [
            //sem id para possivel uso em inserir()
            ':nome'        => $torneio->getNome(),
            ':data'        => $torneio->getData(),
            ':horario'     => $torneio->getHorario(),
            ':formato'     => $torneio->getFormato(),
            ':etapas'      => $torneio->getEtapas(),
            ':garantida'   => $torneio->getGarantida(),
            ':blinds'      => $torneio->getBlinds(),
            ':premiacao'   => $torneio->getPremiacao(),
            ':ranking'     => $torneio->isRanking() ? 1 : 0,
            ':jackpot'     => $torneio->isJackpot() ? 1 : 0
        ];
    }

    //Preenche instancia de torneio com dados do array bruto retornado pelo banco
    private function mapearObjeto(array $linha): Torneio {
        $t = new Torneio();
        $t->setId((int)$linha['id']);
        $t->setNome($linha['nome']);
        $t->setData($linha['data']);
        $t->setHorario($linha['horario']);
        $t->setFormato($linha['formato']);
        $t->setEtapas($linha['etapas'] ?? null);
        $t->setGarantida($linha['garantida'] !== null ? (float)$linha['garantida'] : null);
        $t->setBlinds($linha['blinds']);
        $t->setPremiacao($linha['premiacao']);
        $t->setRanking((bool)$linha['ranking']);
        $t->setJackpot((bool)$linha['jackpot']);
        
        return $t;
    }
}
<?php

require_once __DIR__ . '/../Model/ItemCaixa.php';
/**
 * Classe Data Access Object para entidade ItemCaixa
 * Responsavel por executar todas as operações de CRUD no banco
 */
class ItemDAO {
    private PDO $pdo;
    //Recebe e guarda a conexão com banco, assim permitindo que 
    //todos metodos da classe reutilizarem a mesma conexao PDO
    public function __construct(PDO $pdo) {
        //Pega conexao do arquivo de conexao e guarda na variavel local
        $this->pdo = $pdo;
    }

    //Salva ou Atualiza um item do caixa
    public function salvar(ItemCaixa $item): bool {
        if ($item->getId() === null) {
            return $this->inserir($item);
        } else {
            return $this->atualizar($item);
        }
    }

    private function inserir(ItemCaixa $item): bool {
        $sql = "INSERT INTO itemscaixa (
                    torneio_id, item, valor, fichas, taxa_adm, limite_jogador
                ) VALUES (
                    :torneio_id, :item, :valor, :fichas, :taxa_adm, :limite_jogador
                )";

        $stmt = $this->pdo->prepare($sql);
        $sucesso = $stmt->execute($this->getParametros($item));

        if ($sucesso) {
            $item->setId((int) $this->pdo->lastInsertId());
        }

        return $sucesso;
    }

    private function atualizar(ItemCaixa $item): bool {
        $sql = "UPDATE itemscaixa SET 
                    torneio_id = :torneio_id, 
                    item = :item, 
                    valor = :valor, 
                    fichas = :fichas, 
                    taxa_adm = :taxa_adm, 
                    limite_jogador = :limite_jogador 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $params = $this->getParametros($item);
        //Busca id separadamente porque somente atualizar faz uso dele
        $params[':id'] = $item->getId();

        return $stmt->execute($params);
    }

    //Busca todos os itens de caixa de um torneio específico
    public function buscarPorTorneio(int $torneioId): array {
        $sql = "SELECT * FROM itemscaixa WHERE torneio_id = :torneio_id ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':torneio_id' => $torneioId]);

        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $itens = [];

        foreach ($dados as $linha) {
            $itens[] = $this->mapearObjeto($linha);
        }

        return $itens;
    }

    //Busca um item específico pelo ID
    public function buscarPorId(int $id): ?ItemCaixa {
        $sql = "SELECT * FROM itemscaixa WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        return $linha ? $this->mapearObjeto($linha) : null;
    }

    //Deleta um item do caixa pelo ID
    public function deletar(int $id): bool {
        $sql = "DELETE FROM itemscaixa WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deletarPorTorneio(int $torneioId): bool {
        $sql = "DELETE FROM itemscaixa WHERE torneio_id = :torneio_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':torneio_id', $torneioId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    //Mapeia o objeto ItemCaixa para o array de parâmetros do PDO
    private function getParametros(ItemCaixa $item): array {
        return [
            //sem id para possivel uso em inserir()
            ':torneio_id'     => $item->getTorneioId(),
            ':item'           => $item->getItem(),
            ':valor'          => $item->getValor(),
            ':fichas'         => $item->getFichas() ?? 0,
            ':taxa_adm'       => $item->getTaxaAdm() ?? 0,
            ':limite_jogador' => $item->getLimiteJogador() ?? 0
        ];
    }

    //Preenche instância de ItemCaixa com dados do array bruto retornado pelo banco
    private function mapearObjeto(array $dados): ItemCaixa {
        $item = new ItemCaixa(
            $dados['item'],
            (float) $dados['valor'],
            (int) $dados['fichas']
        );

        $item->setId((int) $dados['id']);
        $item->setTorneioId((int) $dados['torneio_id']);
        $item->setItem($dados['item'] ?? '');
        $item->setValor($dados['valor'] ?? 0);
        $item->setFichas($dados['fichas'] ?? 0);
        $item->setTaxaAdm($dados['taxa_adm'] !== null ? (float) $dados['taxa_adm'] : null);
        $item->setLimiteJogador($dados['limite_jogador'] !== null ? (int) $dados['limite_jogador'] : null);

        return $item;
    }
}
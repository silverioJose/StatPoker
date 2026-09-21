<?php
//Representa os dados e regras de negocio de um torneio de poker
class Torneio {
    private ?int $id = null;
    private string $nome;
    private string $data;
    private string $horario;
    private string $formato;
    private ?string $etapas = null;
    private ?float $garantida = null;
    private string $blinds;
    private string $premiacao = 'Automática — 12% do field premiado';
    private bool $ranking = false;
    private bool $jackpot = false;

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = trim($nome); }

    public function getData(): string { return $this->data; }
    public function setData(string $data): void { $this->data = $data; }

    public function getHorario(): string { return $this->horario; }
    public function setHorario(string $horario): void { $this->horario = $horario; }

    public function getFormato(): string { return $this->formato; }
    public function setFormato(string $formato): void { $this->formato = $formato; }

    public function getEtapas(): ?string { return $this->etapas; }
    public function setEtapas(?string $etapas): void { $this->etapas = $etapas; }

    public function getGarantida(): ?float { return $this->garantida; }
    public function setGarantida(?float $garantida): void { $this->garantida = $garantida; }

    public function getBlinds(): string { return $this->blinds; }
    public function setBlinds(string $blinds): void { $this->blinds = $blinds; }

    public function getPremiacao(): string { return $this->premiacao; }
    public function setPremiacao(?string $premiacao): void { 
        $this->premiacao = (!empty($premiacao)) ? $premiacao : 'Automática — 12% do field premiado'; 
    }

    public function isRanking(): bool { return $this->ranking; }
    public function setRanking(bool $ranking): void { $this->ranking = $ranking; }

    public function isJackpot(): bool { return $this->jackpot; }
    public function setJackpot(bool $jackpot): void { $this->jackpot = $jackpot; }

    //Calcula dinamicamente o status atual do torneio com base na data e hora
    //Compara data/hora atual com horario de abertura (-6h do inicio) e estimatica de encerramento (+6h do inicio)
    public function getStatus(): array {
        $agora = new DateTime();
        $inicio = new DateTime($this->data . ' ' . $this->horario);

        //Clona instancia para evitar modificação do objeto original
        $aberturaInscricoes = (clone $inicio)->modify('-6 hours');
        $fimAproximado = (clone $inicio)->modify('+6 hours');

        if ($agora < $aberturaInscricoes) {
            return [
                'status' => 'soon',
                'label' => 'AGENDADO',
                'badgeClass' => 'badge-agendado'
            ];
        } elseif ($agora >= $aberturaInscricoes && $agora < $inicio) {
            return [
                'status' => 'reg',
                'label' => 'INSCRIÇÕES ABERTAS',
                'badgeClass' => 'badge-inscricoes'
            ];
        } elseif ($agora >= $inicio && $agora <= $fimAproximado) {
            return [
                'status' => 'live',
                'label' => 'AO VIVO',
                'badgeClass' => 'badge-aovivo'
            ];
        } else {
            return [
                'status' => 'done',
                'label' => 'ENCERRADO',
                'badgeClass' => 'badge-encerrado'
            ];
        } 
    }
}
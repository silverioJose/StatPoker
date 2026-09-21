<?php
//Representa os itens de entrada/compra de um torneio de poker
class ItemCaixa {
    private ?int $id = null;
    private ?int $torneioId = null;
    private string $item = '';
    private float $valor = 0.0;
    private ?int $fichas = null;
    private ?float $taxaAdm = null;
    private ?int $limiteJogador = null;

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTorneioId(): ?int { return $this->torneioId; }
    public function setTorneioId(int $torneioId): void { $this->torneioId = $torneioId; }

    public function getItem(): string { return $this->item; }
    public function setItem(string $item): void { $this->item = trim($item); }

    public function getValor(): float { return $this->valor; }
    public function setValor(float $valor): void { 
        $this->valor = max(0.0, $valor); } //Garante valor positivo

    public function getFichas(): ?int { return $this->fichas; }
    public function setFichas(?int $fichas): void { $this->fichas = $fichas; }

    public function getTaxaAdm(): ?float { return $this->taxaAdm; }
    public function setTaxaAdm(?float $taxaAdm): void { $this->taxaAdm = $taxaAdm; }

    public function getLimiteJogador(): ?int { return $this->limiteJogador; }
    public function setLimiteJogador(?int $limiteJogador): void { $this->limiteJogador = $limiteJogador; }
}
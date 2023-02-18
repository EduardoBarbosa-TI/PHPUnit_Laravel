<?php

namespace App\Models;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->ehDoUltimousuario($lance))
        {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $totalLancesUsuario = $this
            ->quantidaDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5) 
        {
            throw new \DomainException('Usuário não pode porpor mais de 5 lances por leilão');
        }
        $this->lances[] = $lance;
    }
    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
    public function finaliza()
    {
        $this->finalizado = true;
    }
    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }
    private function quantidaDeLancesPorUsuario(Usuario $usuario): int 
    {
        $totalLancesUsuario = array_reduce($this->lances, function($totalAcumulado, Lance $lanceAtual) use ($usuario){
            if($lanceAtual->getUsuario() == $usuario){
                return $totalAcumulado +1;
            }

            return $totalAcumulado;

        }, 0);

        return $totalLancesUsuario;

    }
    private function ehDoUltimousuario(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }
}

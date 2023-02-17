<?php

namespace App\Models;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->ehDoUltimousuario($lance)){
            return;
        }

        $totalLancesUsuario = $this
            ->quantidaDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5) {
            return;
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

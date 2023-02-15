<?php

namespace App\Http\Controllers;
use App\Models\Lance;
use App\Models\Leilao;

class Avaliador 
{
    private $maiorValor = -INF;
    private $menorValor =  INF;

    private $maioresLances;
    public function avalia(Leilao $leilao):void
    {
        foreach($leilao->getLances() as $lance){
            if($lance->getValor() > $this->maiorValor){
                $this->maiorValor = $lance->getValor();
            } 
            if($lance->getValor() < $this->menorValor){
                $this->menorValor = $lance->getValor();
            }
        }
        
        $lances = $leilao->getLances();
        usort($lances, function(Lance $primeiroLance, Lance $segundoLance){
            return  $segundoLance->getValor() - $primeiroLance->getValor();
        });
        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMaioresLance(): array
    {
        return $this->maioresLances;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;   
    }
}
<?php

namespace ALura\Leilao\Tests\Model;

use App\Models\Lance;
use App\Models\Leilao;
use App\Models\Usuario;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveRececeberLancesRepetidos()
    {
        $leilao = new Leilao('Variante');
        $ana =  new Usuario('Ana');
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));

        static::assertCount(1, $leilao->getLances());
        static::assertEquals(1000, $leilao->getLances()[0]->getValor());
    }


    #[DataProvider('geraLances')]
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ){    
        static::assertCount($qtdLances, $leilao->getLances());
        foreach( $valores as $i => $valoresEsperados){
            static::assertEquals($valoresEsperados, $leilao->getLances() [$i]->getValor());
        }
    }

    public static function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances =  new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));
        $leilaoCom1Lances = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lances->recebeLance(new Lance($maria, 5000));

        return [
            "Segundo Lance" =>[2, $leilaoCom2Lances, [1000,2000]],
            "Primeiro Lance" =>[1, $leilaoCom1Lances, [5000]]
        ];
    }
}
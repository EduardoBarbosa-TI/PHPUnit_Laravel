<?php

namespace Alura\Leilao\Tests\Service;

use App\Models\Lance;
use App\Models\Leilao; 
use App\Models\Usuario;
use App\Http\Controllers\Avaliador;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AvaliadorTest extends TestCase
{
    /** @var Avaliador */

    private $leiloeiro;
    public function setUp(): void
    {
        $this->leiloeiro= new Avaliador();
    }

    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarMaiorLance(Leilao $leilao)
    {     
        $this->leiloeiro->avalia($leilao);    

        $maiorValor = $this->leiloeiro->getMaiorValor();

        self::assertEquals(4000, $maiorValor);
    }
    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarMenorLance(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);    

        $menorValor = $this->leiloeiro->getMenorValor();

        self::assertEquals(1700, $menorValor);
    }
    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveBuscarOs3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLance();
        static::assertCount(3, $maiores);
        static::assertEquals(4000, $maiores[0]->getValor());
        static::assertEquals(2000, $maiores[1]->getValor());
        static::assertEquals(1700, $maiores[2]->getValor());

    }

    public function testeLeilaoVazioNaoPodeSerAvaliado(){ 
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leilao);
    }

    public static function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana =  new Usuario('Ana');


        $leilao->recebeLance(new Lance($ana,1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 4000));

        return [
            'ordem-crescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana =  new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana,1700));

        return [
           'ordem-decrescente' => [$leilao]
        ];
    }

    public static function leilaoEmOrdemAleatoria(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana =  new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana,1700));
        $leilao->recebeLance(new Lance($maria, 4000));
        
        return [
            'ordem-aleatoria' =>[$leilao]
        ];
    }
}

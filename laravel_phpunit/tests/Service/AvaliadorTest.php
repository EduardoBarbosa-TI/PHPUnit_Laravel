<?php

namespace Alura\Leilao\Tests\Service;

use App\Models\Lance;
use App\Models\Leilao; 
use App\Models\Usuario;
use App\Http\Controllers\Avaliador;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase as FrameworkTestCase;


class AvaliadorTest extends FrameworkTestCase
{
    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarMaiorLance(Leilao $leilao)
    {
        # Arrange - Given
        require 'vendor/autoload.php';

        $leiloeiro = new Avaliador();
        # Act - When
        $leiloeiro->avalia($leilao);    

        $maiorValor = $leiloeiro->getMaiorValor();

        # Assert - Then

        self::assertEquals(4000, $maiorValor);
    }
    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveEncontrarMenorLance(Leilao $leilao)
    {
        require 'vendor/autoload.php';
 
        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);    

        $menorValor = $leiloeiro->getMenorValor();

        self::assertEquals(1700, $menorValor);
    }
    #[DataProvider('leilaoEmOrdemAleatoria')]
    #[DataProvider('leilaoEmOrdemCrescente')]
    #[DataProvider('leilaoEmOrdemDecrescente')]
    public function testAvaliadorDeveBuscarOs3MaioresValores(Leilao $leilao)
    {
        $leiloeiro =  new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiores = $leiloeiro->getMaioresLance();
        static::assertCount(3, $maiores);
        static::assertEquals(4000, $maiores[0]->getValor());
        static::assertEquals(2000, $maiores[1]->getValor());
        static::assertEquals(1700, $maiores[2]->getValor());

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
            [$leilao]
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
            [$leilao]
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
            [$leilao]
        ];
    }
}

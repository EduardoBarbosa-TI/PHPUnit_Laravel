<?php

namespace Alura\Leilao\Tests\Service;

use App\Models\Lance;
use App\Models\Leilao; 
use App\Models\Usuario;

use App\Http\Controllers\Avaliador;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

use function PHPUnit\Framework\assertEquals;

class AvaliadorTest extends FrameworkTestCase
{
    public function testAvaliadorDeveEncontrarMaiorLanceEmOrdemCrescente()
    {
        # Arrange - Given
        require 'vendor/autoload.php';

        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 4000));

        $leiloeiro = new Avaliador();
        # Act - When
        $leiloeiro->avalia($leilao);    

        $maiorValor = $leiloeiro->getMaiorValor();

        # Assert - Then

        self::assertEquals(4000, $maiorValor);
    }
    public function testAvaliadorDeveEncontrarMaiorLanceEmOrdemDecrescente()
    {
        # Arrange - Given
        require 'vendor/autoload.php';

        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 2000));
        
        $leiloeiro = new Avaliador();
        # Act - When
        $leiloeiro->avalia($leilao);    

        $maiorValor = $leiloeiro->getMaiorValor();

        # Assert - Then

        self::assertEquals(4000, $maiorValor);
    }
    public function testAvaliadorDeveEncontrarMenorLanceEmOrdemCrescente()
    {
        # Arrange - Given
        require 'vendor/autoload.php';

        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 4000));

        $leiloeiro = new Avaliador();
        # Act - When
        $leiloeiro->avalia($leilao);    

        $menorValor = $leiloeiro->getMenorValor();

        # Assert - Then

        self::assertEquals(2000, $menorValor);
    }
    public function testAvaliadorDeveEncontrarMenorLanceEmOrdemDecrescente()
    {
        # Arrange - Given
        require 'vendor/autoload.php';

        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');

        $leilao->recebeLance(new Lance($maria, 4000));
        $leilao->recebeLance(new Lance($joao, 2000));
        
        $leiloeiro = new Avaliador();
        # Act - When
        $leiloeiro->avalia($leilao);    

        $menorValor = $leiloeiro->getMenorValor();

        # Assert - Then

        self::assertEquals(2000, $menorValor);
    }

    public function testAvaliadorDeveBuscarOs3MaioresValores()
    {
        $leilao = new Leilao('Fiat 147 0KM');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($ana, 1500));
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($jorge, 1700));

        $leiloeiro =  new Avaliador();
        $leiloeiro->avalia($leilao);

        $maiores = $leiloeiro->getMaioresLance();
        static::assertCount(3, $maiores);
        static::assertEquals(2000, $maiores[0]->getValor());
        static::assertEquals(1700, $maiores[1]->getValor());
        static::assertEquals(1500, $maiores[2]->getValor());

    }
}

<?php

use Embryo\FemininPluriel;
use PHPUnit\Framework\TestCase;

final class FemininPlurielTest extends TestCase
{
    /** @var FemininPluriel */
    private $femininPluriel;

    protected function setUp()
    {
        $this->femininPluriel = new FemininPluriel();
    }

    /** @dataProvider adaptProvider */
    public function testAdapt(string $source, string $expected)
    {
        $this->assertEquals($expected, $this->femininPluriel->adapt($source));
    }

    public function adaptProvider(): \Generator
    {
        yield 'A string without specific markup should return itself' => ['Ce texte ne doit pas être transformé', 'Ce texte ne doit pas être transformé'];
        yield 'A masculine name\'s adjective should be correct' => ['Ce tableau est bleu[|e]', 'Ce tableau est bleu'];
        yield 'A feminine name\'s adjective should be correct' => ['(F)Cette table est bleu[|e]', 'Cette table est bleue'];
        yield 'A masculine singular name\'s adjective should be correct' => ['Un effet spécia[l|le|ux|les]', 'Un effet spécial'];
        yield 'A masculine plural name\'s adjective should be correct' => ['(P)Des effets spécia[l|le|ux|les]', 'Des effets spéciaux'];
        yield 'A feminine singular name\'s adjective should be correct' => ['(F)Une voiture spécia[l|le|ux|les]', 'Une voiture spéciale'];
        yield 'A feminine plural name\'s adjective should be correct' => ['(FP)Des voitures spécia[l|le|ux|les]', 'Des voitures spéciales'];
    }
}

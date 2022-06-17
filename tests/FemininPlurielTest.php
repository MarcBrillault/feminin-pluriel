<?php

namespace Tests;

use Embryo\FemininPluriel;
use PHPUnit\Framework\TestCase;

final class FemininPlurielTest extends TestCase
{
    /**
     * @var FemininPluriel
     */
    private $femininPluriel;

    protected function setUp(): void
    {
        $this->femininPluriel = new FemininPluriel();
    }

    /**
     * @dataProvider adaptProvider
     */
    public function testAdapt(string $source, string $expected): void
    {
        $this->assertEquals($expected, $this->femininPluriel->adapt($source));
    }

    public function adaptProvider(): \Generator
    {
        yield 'A string without specific markup should return itself' =>
            ['Ce texte ne doit pas être transformé', 'Ce texte ne doit pas être transformé'];
        yield 'A masculine name\'s adjective should be correct' =>
            ['Ce tableau est bleu[|e]', 'Ce tableau est bleu'];
        yield 'A feminine name\'s adjective should be correct' =>
            ['(F)Cette table est bleu[|e]', 'Cette table est bleue'];
        yield 'A masculine singular name\'s adjective should be correct' =>
            ['Un effet spécia[l|le|ux|les]', 'Un effet spécial'];
        yield 'A masculine plural name\'s adjective should be correct' =>
            ['(P)Des effets spécia[l|le|ux|les]', 'Des effets spéciaux'];
        yield 'A feminine singular name\'s adjective should be correct' =>
            ['(F)Une voiture spécia[l|le|ux|les]', 'Une voiture spéciale'];
        yield 'A feminine plural name\'s adjective should be correct' =>
            ['(FP)Des voitures spécia[l|le|ux|les]', 'Des voitures spéciales'];
        yield 'An inverted feminine plural name\'s adjective should be correct' =>
            ['(PF)Des voitures spécia[l|le|ux|les]', 'Des voitures spéciales'];
        yield 'Several replacements can occur in the same sentence' =>
            ['(FP)Ce[t|tte|s|s] femmes [est|est|sont|sont] be[au|lle|aux|lles]', 'Ces femmes sont belles'];
        yield 'Several replacements can occur in the same sentence' =>
            ['(P)Ce[t|tte|s|s] hommes [est|est|sont|sont] be[au|lle|aux|lles]', 'Ces hommes sont beaux'];
        yield 'A content with something before the plural information should be transformed as well' =>
            ['Sacrebleu, (P)ce[|tte|s|s] hommes [est|est|sont|sont] be[au|lle|aux|lles] !', 'Sacrebleu, ces hommes sont beaux !'];
    }

    /** @dataProvider splitProvider */
    public function testSplit(string $source, array $expected): void
    {
        $this->assertEquals($expected, $this->femininPluriel->split($source));
    }

    public function splitProvider(): \Generator
    {
        yield 'A string with no marker should return itself encased in an array' => ['Des chips', ['Des chips']];
        yield 'A string with the marker at the beginning should return an array with two elements' => ['(P)Des chips', ['(P)', 'Des chips']];
    }
}

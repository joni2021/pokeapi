<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\clases\Pokemon;

class PokemonTest extends TestCase
{
    private Pokemon $pokemon;

    protected function setUp(): void
    {
        $this->pokemon = new Pokemon;
    }

    public function test_get_all_pokemons_returned_null()
    {
        $this->assertNotNull($this->pokemon->all());
    }

    public function test_get_all_pokemons_returned_iterable_items()
    {
        $this->assertIsIterable($this->pokemon->all());
    }

    public function test_find_not_exist_pokemon_returned_empty()
    {
        $this->assertEmpty($this->pokemon->find("pikachu"));
    }

    public function test_find_one_exist_pokemon_returned_one_pokemon()
    {
        $this->assertCount(1, $this->pokemon->find("bulbasaur"));
    }

    public function test_find_partial_name_return_more_than_one_pokemon()
    {
        $this->assertCount(2, $this->pokemon->find("saur"));
    }

}

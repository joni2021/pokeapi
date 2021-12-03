<?php


namespace Tests\Clases;

use App\Pokemon as PokemonOriginal;
use App\Ability;
use App\Type;
use Illuminate\Support\Collection;

class Pokemon extends PokemonOriginal
{

    public function all()
    {
        $pokemons = json_decode(file_get_contents(__DIR__ . "/../mocks/pokemones.json"), true);

        $pokemons = $pokemons['results'];

        $pokemonsCollection = new Collection();

        foreach($pokemons as $result){

            $pokemon = new Pokemon;
            $pokemon->setName($result["name"]);
            $pokemon->setUrl($result["url"]);

            $id = explode("/", substr($result["url"], 0, -1));
            $id = array_pop($id);

            $pokemon->setId($id);

            $pokemonsCollection->push($pokemon);
        }

        $this->pokemons = $pokemonsCollection;

        return $this->pokemons;
    }


    public function find($name)
    {
        $pokemonsMock = json_decode(file_get_contents(__DIR__ . '/../mocks/pokemones_collection.json'), true);

        $pokemonsCollection = new Collection;

        foreach($pokemonsMock as $result){

            $pokemon = new Pokemon;
            $pokemon->setName($result["name"]);
            $pokemon->setBaseExperience($result["base_experience"]);
            $pokemon->setHeight($result["height"]);
            $pokemon->setWeight($result["weight"]);

            $abilities = new Collection;

            foreach($result["abilities"] as $ability){
                $abilities->push(new Ability($ability["ability"]["name"]));
            }

            $pokemon->setAbilities($abilities);


            $types = new Collection;

            foreach($result["types"] as $type){
                $types->push(new Type($type["type"]["name"]));
            }

            $pokemon->setTypes($types);


            $sprites = new Collection($result["sprites"]);
            $pokemon->setSprites($sprites);

            $pokemonsCollection->push($pokemon);
        }

        $pokemons = new Collection();

        foreach($pokemonsCollection as $pokemon){
            if(is_int(strpos($pokemon->getName(), $name))){
                $pokemons->push($pokemon);
            }
        }

        return $pokemons;
    }

}
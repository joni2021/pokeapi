<?php
namespace App;

use Illuminate\Support\Collection;
use JsonSerializable;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class Pokemon implements JsonSerializable
{

    const URL = "pokemon";

    /**
     * @var Collection
     */
    private $pokemons;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Collection
     */
    private $abilities;

    /**
     * @var int
     */
    private $base_experience;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Collection
     */
    private $sprites;

    /**
     * @var Collection
     */
    private $types;

    /**
     * @var int
     */
    private $weight;


    public function __construct()
    {
        $this->name = '';
        $this->url = '';
        $this->abilities = new Collection;
        $this->base_experience = 0;
        $this->height = 0;
        $this->id = null;
        $this->sprites = new Collection;
        $this->types = new Collection;
        $this->weight = 0;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "url" => $this->getUrl(),
            "abilities" => $this->getAbilities(),
            "base_experience" => $this->getBaseExperience(),
            "height" => $this->getHeight(),
            "sprites" => $this->getSprites(),
            "weight" => $this->getWeight(),
            "types" => $this->getTypes(),
        ];
    }

    /**
     * Lista todos los pokemons ya sea del cache o de la api
     *
     * @return Collection
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function all()
    {
        $cache = new FilesystemAdapter;

        $pokemons = $cache->get('pokemons', function (ItemInterface $item) {
            $item->expiresAfter(86400);

            $api = new Pokeapi;

            $pokemons = $api->getPokemons()->getResultado();

            return $pokemons;
        });

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


    /**
     * Busca pokemons a partir del nombre parcial
     *
     * @param $name
     * @return Collection
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function find($name)
    {
        $pokemons = new Collection();

        foreach($this->all() as $pokemon){
            if(is_int(strpos($pokemon->name, $name))){
                $pokemon->assignProperties();
                $pokemons->push($pokemon);
            }
        }

        return $pokemons;
    }

    /**
     * Asigna las propiedades faltantes al resultado de la búsqueda del usuario
     */
    protected function assignProperties()
    {
        $cache = new FilesystemAdapter;

        $pokemon = $cache->get($this->id, function (ItemInterface $item) {
            $item->expiresAfter(86400);

            $api = new Pokeapi;

            $pokemon = $api->getPokemon($this->id)->getResultado();

            return $pokemon;
        });

        $this->setName($pokemon["name"]);
        $this->setBaseExperience($pokemon["base_experience"]);
        $this->setHeight($pokemon["height"]);
        $this->setWeight($pokemon["weight"]);

        $abilities = new Collection;

        foreach($pokemon["abilities"] as $ability){
            $abilities->push(new Ability($ability["ability"]["name"]));
        }

        $this->setAbilities($abilities);


        $types = new Collection;

        foreach($pokemon["types"] as $type){
            $types->push(new Type($type["type"]["name"]));
        }

        $this->setTypes($types);


        $sprites = new Collection($pokemon["sprites"]);
        $this->setSprites($sprites);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return Collection
     */
    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    /**
     * @return int
     */
    public function getBaseExperience(): int
    {
        return $this->base_experience;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getSprites(): Collection
    {
        return $this->sprites;
    }

    /**
     * @return Collection
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param string $name
     */
    public function setName(string $name = ''): void
    {
        $this->name = $name;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url = ""): void
    {
        $this->url = $url;
    }

    /**
     * @param Collection $abilities
     */
    public function setAbilities(Collection $abilities): void
    {
        $this->abilities = $abilities;
    }

    /**
     * @param int $base_experience
     */
    public function setBaseExperience(int $base_experience): void
    {
        $this->base_experience = $base_experience;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param Collection $sprites
     */
    public function setSprites(Collection $sprites): void
    {
        $this->sprites = $sprites;
    }

    /**
     * @param Collection $types
     */
    public function setTypes(Collection $types): void
    {
        $this->types = $types;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }



}
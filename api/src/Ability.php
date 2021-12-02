<?php
namespace App;

use JsonSerializable;

class Ability implements JsonSerializable
{

    const URL = "ability";

    /**
     * @var string|null
     */
    private $name;


    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            "name" => $this->name
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
<?php
namespace App;

use JsonSerializable;

class Type implements JsonSerializable
{

    const URL = "type";

    /**
     * @var string
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
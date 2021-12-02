<?php
namespace App;

class Pokeapi
{
    private $curl;
    private $header;
    private $httpResultado;
    private $httpCode;
    private $urlBase;

    /**
     * ApiUnidbFunction constructor.
     */
    public function __construct()
    {
        $this->urlBase  = "https://pokeapi.co/api/v2/";
        $this->header   = [
            'Content-Type: application/json',
        ];
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $body
     */
    private function call($url = '', $method = 'GET', Array $body = [])
    {
        $url = str_ireplace($this->urlBase, "", $url);

        $this->init($this->urlBase . $url);

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);

        if (count($body) > 0)
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($body));

        $this->httpResultado = $this->exec();
        $this->httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->close();
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return mixed
     */
    public function getResultado()
    {
        return json_decode($this->httpResultado, true);
    }

    private function close()
    {
        curl_close($this->curl);
    }

    /**
     * @return mixed
     */
    private function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * @param $url
     */
    private function init($url)
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * Listado de pokemons
     */
    public function getPokemons()
    {
        $this->call(Pokemon::URL  . "/?limit=2000");

        return $this;
    }

    /**
     * Detalle de un pokemon
     */
    public function getPokemon($id)
    {
        $this->call(Pokemon::URL . "/" . $id);

        return $this;
    }


}
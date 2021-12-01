<?php
namespace App;

class Pokeapi
{
    protected $curl;
    protected $header;
    protected $httpResultado;
    protected $httpCode;
    protected $urlBase;

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
    public function call($url = '', $method = 'GET', Array $body = [])
    {
        $this->init($url);

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
        return json_decode($this->httpResultado);
    }

    protected function close()
    {
        curl_close($this->curl);
    }

    /**
     * @return mixed
     */
    protected function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * @param $url
     */
    protected function init($url)
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * Listado de sedes
     */
    public function getSedes(){

        $this->call(config('app.API_MDS_URL').'/unidb/sedes?search='.'&limit=500');

    }

}
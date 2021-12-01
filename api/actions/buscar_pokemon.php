<?php
require_once "../vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] != 'GET'){
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    echo json_encode(["status" => "error", "message" => "Método inválido"]);
    exit;
}

$pokemons = json_decode(file_get_contents("../src/prueba.json"), true);

echo json_encode(["status" => "ok", "data" => [$pokemons]]);
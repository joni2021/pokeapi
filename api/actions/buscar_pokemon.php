<?php

require_once "../vendor/autoload.php";
header("Content-Type: application/json");

use \App\Pokemon;

if($_SERVER['REQUEST_METHOD'] != 'GET'){
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    echo json_encode(["status" => "error", "message" => "Método inválido"]);
    exit;
}

$busqueda = urldecode($_GET["pokemon"]);

try {
    $pokemon = new Pokemon;

    $resultados = $pokemon->find($busqueda);

    echo json_encode(["status" => "ok", "data" => $resultados]);
    exit;

}catch (Exception $e){
    header($_SERVER["SERVER_PROTOCOL"]." 404", true, 404);
    echo json_encode(["status" => "error", "message" => "Hubo un problema al buscar su pokemon"]);
}

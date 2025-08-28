<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../api_core/resposta.php";

$recurso = $_GET['recurso'];
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

//Pegar dados do corpo da requisição, enviado pelo ajax 
$dadosJSON = file_get_contents("php://input") ?? null;

//Iniciar cURL
$curl = curl_init();
$URL = null;

if ($id) {
    $URL = "http://localhost/TPW3DPWEBPLS/aceder.xml/Projecto%20PI%20-%20Gerenciador%20de%20tarefas/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}&id={$id}";
} else {
    $URL = "http://localhost/TPW3DPWEBPLS/aceder.xml/Projecto%20PI%20-%20Gerenciador%20de%20tarefas/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}";
}

curl_setopt($curl, CURLOPT_URL, $URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

switch ($method) {
    case "GET":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        break;
    case "POST":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dadosJSON);
        break;
    case "PUT":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dadosJSON);
        break;
    case "DELETE":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        break;
    default:
        echo Resposta::json(405, "Metodo de requisição não existe");
}

$resposta = curl_exec($curl);
echo $resposta;
curl_close($curl);

<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
require_once __DIR__ . "/../../api_core/resposta.php";

$recurso = $_GET['recurso'];
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$search = $_GET['search'] ?? null;
$token = $_COOKIE["tpwSSID"];

//Pegar dados do corpo da requisição, enviado pelo ajax 
$dadosJSON = file_get_contents("php://input") ?? null;

//Definindo headers
$headers = [
    "X-Token: $token",
];

//Iniciar cURL
$curl = curl_init();
//Adapte a URL base para o seu projecto, considerando o seu localhost
$URL = "http://localhost/DPWDPLS/EC/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}";

if ($id) {
    $URL .= "&id={$id}";
}

if ($search) {
    $URL .= "&search={$search}";
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
        echo Resposta::json(400, "Metodo de requisição não existe");
}

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$resposta = curl_exec($curl);
echo $resposta;
curl_close($curl);

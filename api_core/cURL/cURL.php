<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
require_once __DIR__ . "/../../api_core/resposta.php";

$recurso = $_GET['recurso'];
$method  = $_SERVER['REQUEST_METHOD'];
$id      = $_GET['id'] ?? null;
$search  = $_GET['search'] ?? null;
$token   = $_COOKIE["tpwSSID"];

// Rotas públicas (não precisam de token)
$rotasPublicas = ['usuario'];

// Pegar dados do corpo da requisição enviado pelo AJAX
$dadosJSON = file_get_contents("php://input") ?? null;

// Iniciar cURL
$curl = curl_init();

if(!in_array($recurso, $rotasPublicas)){
    if(!$token){
        echo Resposta::json(401, "Token vazio. Faça login novamente.");
        exit;
    }
    $URL = "http://localhost/DPWDPLS/EC/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}";
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "X-Token: $token",
        "Content-Type: application/json"
    ]);
} else {
    $URL = "http://localhost/DPWDPLS/EC/Gerenciador-de-tarefas/public/rotas_publicas/index.php?recurso={$recurso}";
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
}

if ($id) {
    $URL .= "&id={$id}";
}

if ($search) {
    $URL .= "&search={$search}";
}

curl_setopt($curl, CURLOPT_URL, $URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Configurar método
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
        exit;
}

$resposta = curl_exec($curl);
if(curl_errno($curl)){
    $erro = curl_error($curl);
    error_log("Erro cURL: $erro \n", 3,  __DIR__ . "/../../Erro_log_per.log");
    echo Resposta::json(500, "Servidor em manutenção");
    exit;
}
echo $resposta;
curl_close($curl);



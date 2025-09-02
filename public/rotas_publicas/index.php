<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once __DIR__ . "/../../Controller/Usuario.php";
require_once __DIR__ . "/../../conexao.php";
require_once __DIR__ . "/../../api_core/resposta.php";
require_once __DIR__ . "/../../models/SessaoDAO.php";

$recurso = $_GET['recurso'] ?? null;
$method  = $_SERVER['REQUEST_METHOD'];
$dados   = json_decode(file_get_contents('php://input'), true) ?? null;

if ($recurso === "usuario") {

    switch ($method) {
        case "GET":
            // Implementar se necessário
            break;
        case "POST":
            UsuarioController::cadastrarUsuario($dados['nome'], $dados['email'], $dados['senha']);
            break;
        default:
            echo Resposta::json(405, "Método não permitido");
            break;
    }
}

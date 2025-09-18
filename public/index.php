<?php 
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");

require_once __DIR__ ."/../Controller/Etiqueta.php";
require_once __DIR__ ."/../Controller/Tarefa.php";
require_once __DIR__ ."/../Controller/Usuario.php";
require_once __DIR__ ."/../conexao.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../models/SessaoDAO.php";

$recurso = $_GET['recurso'] ?? null;

    function sessaoInvalida(){
        echo Resposta::json(401, "Token inválido ou expirado. Faça login novamente.");
        exit;
    }

    $heders = getallheaders();

    $token = $heders['X-Token']; 
    try {
        if (!$token) {
            sessaoInvalida();
        }

        $isValid = SessaoDAO::verificarSessao($token) ?? null;
        if (!$isValid) {
            sessaoInvalida();
        }

        $id_Usuario = SessaoDAO::getIdUsuario($token) ?? null;

    } catch (Exception $e) {
        sessaoInvalida();
    }

$id      = $_GET['id'] ?? null;
$method  = $_SERVER['REQUEST_METHOD'] ?? null;
$search = $_GET['search'] ?? null;
$dados   = json_decode(file_get_contents('php://input'), true) ?? null;
$acao    = $_GET['acao'] ?? null;

if ($recurso === "tarefa") {
    switch ($method) {
        case "GET":
            if($search){
                TarefaController::pesquisarTarefas($search, $id_Usuario);
                exit;
            }
            if($id){
                TarefaController::buscarTarefaPorId($id);
                exit;
            }
            if($id_Usuario){
                TarefaController::getTarefas($id_Usuario);
            }
            break;
        case "POST":
                TarefaController::adicionarTarefa($dados, $id_Usuario);
            break;
        case "PUT":
            if($dados){
                TarefaController::atualizarTarefa($dados, $id_Usuario);
            }
            break;
        case "PATCH":
            if($dados['em_andamento'] ?? null){ 
                TarefaController::definirComoEmAndamento($id);
                exit;
            }
            if($dados['concluir'] ?? null){ 
                TarefaController::concluirTarefa($id);
                exit;
            }
            break;
        case "DELETE":
            TarefaController::removerTarefa($id);
            break;
        default:
            echo Resposta::json(405, "Método não permitido");
            break;
    }
}

if ($recurso === "etiqueta") {
    switch ($method) {
        case "GET":
            if($id){
                EtiquetasController::buscarEtiquetaPorId($id);
            }
            EtiquetasController::getEtiquetas($id_Usuario);
            break;
        case "POST":
                EtiquetasController::adicionarEtiqueta($dados["nome"] ?? null, $dados['cor'] ?? null, $id_Usuario, $id);
                break;
        default:
            echo Resposta::json(405, "Método não permitido");
            break;
    }
}
?>

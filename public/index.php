<?php 
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once __DIR__ ."/../Controller/Etiqueta.php";
require_once __DIR__ ."/../Controller/Tarefa.php";
require_once __DIR__ ."/../Controller/Usuario.php";
require_once __DIR__ ."/../conexao.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../models/SessaoDAO.php";

$recurso = $_GET['recurso'] ?? null;

    function alertRedirect($mensagem, $url = '../../index.php'){
        die($mensagem);
        header("Location: $url");
        exit;
    }

    $heders = getallheaders();

    $token = $heders["X-Token"] ?? null;

    try {
        if (!$token) {
            alertRedirect('Token vazio. Faça login novamente.');
        }

        $isValid = SessaoDAO::verificarSessao($token);
        if (!$isValid) {
            alertRedirect('Sessão inválida. Faça login novamente.');
        }

        $id_Usuario = SessaoDAO::getIdUsuario($token);

    } catch (Exception $e) {
        alertRedirect('Sessao expirada');
        exit;
    }

$id      = $_GET['id'] ?? null;
$method  = $_SERVER['REQUEST_METHOD'];
$search = $_GET['search'] ?? null;
$dados   = json_decode(file_get_contents('php://input'), true) ?? null;

if ($recurso === "tarefa") {
    switch ($method) {
        case "GET":
            if($search){
                TarefaController::pesquisarTarefas($search);
            }elseif($id){
                TarefaController::buscarTarefaPorId($id);
            }else{
                TarefaController::getTarefas($id_Usuario);
            }
            break;
        case "POST":
            TarefaController::adicionarTarefa($dados, $id_Usuario);
            break;
        case "PUT":
            TarefaController::atualizarTarefa($dados, $id_Usuario);
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
        default:
            echo Resposta::json(405, "Método não permitido");
            break;
    }

    if($recurso === "usuario"){

        switch($method){
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
}
?>

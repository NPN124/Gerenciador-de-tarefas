<?php 
require_once __DIR__ ."/../Controller/Tarefa.php";
require_once __DIR__ ."/../conexao.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../models/SessaoDAO.php";

header("Content-Type: application/json");

function alertRedirect($mensagem, $url = '../../index.php') {
    echo "<script>
        alert('{$mensagem}');
        window.location.href = '{$url}';
    </script>";
    exit; 
}

$token = "token_68af47707b0c04.29683538";

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

$recurso = $_GET['recurso'] ?? null;
$id      = $_GET['id'] ?? null;
$method  = $_SERVER['REQUEST_METHOD'];
$dados   = json_decode(file_get_contents('php://input'), true) ?? null;

if ($recurso === "tarefa") {
    switch ($method) {
        case "GET":
            TarefaController::getTarefas($id_Usuario);
            break;

        case "POST":
            TarefaController::adicionarTarefa($dados, $id_Usuario);
            break;

        case "PUT":
            TarefaController::atualizarTarefa($dados, $id_Usuario);
            break;

        case "DELETE":
            if ($id) {
                TarefaController::removerTarefa($idTarefa, $id_Usuario);
            } else {
                echo Resposta::json(400, "ID não informado para exclusão");
            }
            break;

        default:
            echo Resposta::json(405, "Método não permitido");
            break;
    }
}
?>

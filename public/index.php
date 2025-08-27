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

$token = "token_68aede1f8dab74.92247956";

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

    $rescurso = $_GET['recurso'];
    $id = $GET['id'] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];
    $dados = json_decode(file_get_contents('php://input'), true) ?? null;

    if($rescurso == "tarefa"){

        switch($method){

            case "GET":
                TarefaController::getTarefas($id_Usuario);
                break;
            case "POST":
                TarefaController::adicionarTarefa($dados, $id_Usuario);
                break;
        }
    }
?>
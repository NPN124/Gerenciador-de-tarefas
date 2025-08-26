<?php 
require_once __DIR__ ."/../Controller/Tarefa.php";
require_once __DIR__ ."/../conexao.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../models/SessaoDAO.php";

header("Content-Type: application/json");

function alertRedirect($mensagem, $url = '../index.php') {
    echo "<script>
        alert('{$mensagem}');
        window.location.href = '{$url}';
    </script>";
    exit; 
}

$token = $_COOKIE['tpwSSID'] ?? null;

try {
    if (!$token) {
        alertRedirect('Token vazio. Faça login novamente.');
    }

    $isValid = SessaoDAO::verificarSessao($token);
    if (!$isValid) {
        alertRedirect('Sessão inválida. Faça login novamente.');
    }

    $id_Usuario = SessaoDAO::getIdUsuario($token);

} catch (\Throwable $e) {
    alertRedirect('Sessao expirada');
    exit;
}

    $rescurso = $_GET['recurso'];
    $id = $GET['id'] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];

    if($rescurso == "tarefa"){

        switch($method){

            case "GET":
                TarefaController::getTarefas($id_Usuario);
        }
    }
?>
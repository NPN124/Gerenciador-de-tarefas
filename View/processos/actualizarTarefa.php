<?php
require_once __DIR__ . "/../../api_core/configracao.php";

$token = $_COOKIE['tpwSSID'] ?? null;
$tarefaId = $_GET['id'] ?? null;

$titulo = $_POST['titulo'] ?? '';
$prioridade = $_POST['prioridade'] ?? '';
$prazo = $_POST['prazo'] ?? '';
$status = $_POST['status'] ?? '';
$descricao = $_POST['descricao'] ?? '';

$curl = curl_init();
curl_setopt($curl, CURLOPT_PROXY, '');
curl_setopt_array($curl, [
    CURLOPT_URL => URL_BASE . "?recurso=tarefa&id={$tarefaId}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => json_encode([
        "titulo" => $titulo,
        "prioridade" => $prioridade,
        "prazo" => $prazo,
        "status" => $status,
        "descricao" => $descricao
    ]),
    CURLOPT_HTTPHEADER => [
        "X-Token: $token",
        "Content-Type: application/json"
    ],
]);

$resposta = curl_exec($curl);

if (curl_errno($curl)) {
    $erro = curl_error($curl);
    error_log("Erro cURL: $erro", 3, __DIR__ . "/../../Erro_log_per.log");
    echo "<script>alert('Erro de comunicação com o servidor'); window.location.href='../Menu.php';</script>";
    exit;
}

$resposta = json_decode($resposta, true);

if (isset($resposta['status']) && $resposta['status'] == 200) {
    echo "<script>alert('" . htmlspecialchars($resposta['mensagem']) . "'); 
    window.location.href='../Menu.php?recurso=tarefa';</script>";
} elseif (isset($resposta['status']) && $resposta['status'] == 401) {
    echo "<script>alert('" . htmlspecialchars($resposta['mensagem']) . "'); 
    window.location.href='../index.php';</script>";
} else {
    $mensagem = $resposta['mensagem'] ?? 'Erro desconhecido';
    echo "<script>alert('" . htmlspecialchars($mensagem) . "'); 
    window.location.href='../Menu.php?recurso=tarefa';</script>";
}

curl_close($curl);
?>

<?php
require_once __DIR__ ."/../../api_core/configracao.php";
$recurso = "tarefa"; 
$token = $_COOKIE['tpwSSID'] ?? null;

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => URL_BASE . "?recurso={$recurso}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode(["titulo" => $_POST['titulo']]),
    CURLOPT_HTTPHEADER => [
        "X-Token: $token",
        "Content-Type: application/json"
    ],
]);

$resposta = curl_exec($curl);
$dadosResposta = json_decode($resposta, true);

if (curl_errno($curl)) {
    $erro = curl_error($curl);
    error_log("Erro cURL: $erro", 3, __DIR__ . "/../../Erro_log_per.log");
    echo "<script>alert('Erro de comunicação com o servidor'); 
        window.location.href='../Menu.php;</script>";
    exit;
}

if (isset($dadosResposta['status'])) {
    if ($dadosResposta['status'] == 200) {
        echo "<script>alert('" . $dadosResposta['mensagem'] . "'); 
        window.location.href='../Menu.php';
        </script>";
    } else if ($dadosResposta['status'] == 401) {
        echo "<script>alert('" . $dadosResposta['mensagem'] . "'); 
        window.location.href='../Menu.php';</script>";
    } else {
        $mensagem = $dadosResposta['mensagem'];
        echo "<script>alert('$mensagem'); 
        window.location.href='../Menu.php';</script>";
    }
} else {
    echo "<script>alert('Resposta inválida do servidor'); 
        window.location.href='../Menu.php';</script>";
}

curl_close($curl);
?>
<?php 
require_once __DIR__ . "/../../api_core/configracao.php";
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");

$recurso = $_GET['recurso'] ?? null;
$token = $_COOKIE['tpwSSID'] ?? null;
$tarefaId = $_GET['tarefa'] ?? null;

$curl = curl_init();

curl_setopt($curl, CURLOPT_PROXY, '');

if($tarefaId){
    curl_setopt_array($curl, [
    CURLOPT_URL => URL_BASE."?recurso=tarefa&id={$tarefaId}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "PATCH",
    CURLOPT_HTTPHEADER => [
        "X-Token: $token",
    ],
    CURLOPT_POSTFIELDS => json_encode(["concluir" => true])
]);
}
$resposta = json_decode(curl_exec($curl), true);

if (curl_errno($curl)) {
    $erro = curl_error($curl);
    error_log("Erro cURL: $erro \n", 3,  __DIR__ . "/../../Erro_log_per.log");
    echo "<script>alert('Erro de comunicação com o servidor')<script>";
    exit;
}
if ($resposta['status'] == 200) {
    echo "<script>alert('" . $resposta['mensagem'] . "'); 
    window.location.href='../Menu.php?recurso=tarefa';</script>";
} else if ($resposta['status' == 401]) {
    echo "<script>alert('" . $resposta['mensagem'] . "'); 
    window.location.href='../index.php';</script>";
}else{
    echo "<script>alert('" . $resposta['mensagem'] . "'); 
    window.location.href='../Menu.php?recurso=tarefa';</script>";
}

curl_close($curl);
?>
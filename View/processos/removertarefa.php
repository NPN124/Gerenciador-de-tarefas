<?php 
require_once __DIR__ ."/../../api_core/configracao.php";
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

$id     = $_GET['id'] ?? null;
$token = $_COOKIE['tpwSSID'];

$curl = curl_init();
curl_setopt($curl, CURLOPT_PROXY, '');
if($id){
    curl_setopt_array($curl, [
    CURLOPT_URL => URL_BASE ."?recurso=tarefa&id={$id}",
    CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
        "X-Token: $token",
    ],
    CURLOPT_CUSTOMREQUEST => "DELETE"
]);
}
$resposta = json_decode(curl_exec($curl), true);

if(curl_errno($curl)){
    $erro = curl_error($curl);
    error_log("Erro cURL: $erro \n", 3,  __DIR__ . "/../Erro_log_per.log");
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
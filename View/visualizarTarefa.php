<?php
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
require_once __DIR__ . "/../api_core/configracao.php";

$token = $_COOKIE['tpwSSID'] ?? null;
$id     = $_GET['id'] ?? null;

function requisitarAPI($url, $token)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-Token: $token",
        ],
        CURLOPT_CUSTOMREQUEST => "GET"
    ]);

    $response = json_decode(curl_exec($curl), true);
    if (curl_errno($curl)) {
        $erro = curl_error($curl);
        error_log("Erro cURL: $erro \n", 3, __DIR__ . "/../Erro_log_per.log");
        echo "<script>alert('Erro de comunicação com o servidor');</script>";
        curl_close($curl);
        return [];
    }

    curl_close($curl);

    if (!isset($response['status'])) return [];

    if ($response['status'] == 200) {
        return $response;
    } elseif ($response['status'] == 401) {
        echo "<script>alert('{$response['mensagem']}');
         window.location.href='../index.php';</script>";
        exit;
    } else {
        echo "<script>alert('{$response['mensagem']}');
         window.location.href='Menu.php?recurso=tarefa';</script>";
        exit;
    }
}
$thisUrl = URL_BASE . "?recurso=tarefa&id=" . urlencode($id);
$tarefaDetalhada = requisitarAPI($thisUrl, $token);

if ($tarefaDetalhada && isset($tarefaDetalhada['dados'])) {
    $tarefa = $tarefaDetalhada['dados'];
} else {
    echo "<script>alert('Tarefa não encontrada');
         window.location.href='Menu.php?recurso=tarefa';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar tarefa</title>
    <link rel="stylesheet" href="../style/Tarefas/tarefas.css">
    <link rel="stylesheet" href="../style//Tarefas/mesagens.css">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <link rel="stylesheet" href="../style/Tarefas/formulario-principal.css">
    <link rel="stylesheet" href="../style/Tarefas/visualizarTarefa.css">
    <link rel="stylesheet" href="fontawesome-free-7.0.0-web/css/all.min.css">
</head>

<body>
    <div class="visualizar-tarefa">
        <h2 id="titulo-visualizacao">Título da tarefa</h2>

        <div class="tarefa-detalhes">
            <p><strong>Prioridade:</strong> <span id="prioridade-visualizacao"><?php echo $tarefa['prioridade'] ?></span></p>
            <p><strong>Status:</strong> <span id="status-visualizacao"><?php echo $tarefa['status'] ?></span></p>
            <p><strong>Prazo:</strong> <span id="prazo-visualizacao"><?php echo $tarefa['prazo'] ?></span></p>
        </div>

        <div class="descricao-container">
            <p><strong>Descrição:</strong></p>
            <p id="descricao-visualizacao"><?php echo $tarefa['descricao'] ?></p
        </div>

        <div class="form-actions">
            <button id="btn-fechar-visualizacao" class="btn-fechar"> <a href="./Menu.php">Voltar</a> </button>
        </div>
    </div>
    <script type="text/javascript" src="../Javascript/Jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="../Javascript/Efeitos.js"></script>
    <script type="text/javascript" src="../Javascript/visualizar.js"></script>
</body>

</html>
<?php
require_once __DIR__ . "/../api_core/configracao.php";

$recurso = "tarefa"; 
$token = $_COOKIE['tpwSSID'] ?? null;
$tarefaId = $_GET['id'] ?? null;

if (!$token || !$tarefaId) {
    echo "<script>alert('Token ou ID da tarefa ausente.'); window.location.href='../Menu.php';</script>";
    exit;
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_PROXY, '');
curl_setopt_array($curl, [
    CURLOPT_URL => URL_BASE . "?recurso={$recurso}&id={$tarefaId}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "GET",
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

$dadosResposta = json_decode($resposta, true);

$tarefa = $dadosResposta['dados'] ?? null;
$titulo = $tarefa['titulo'] ?? '';
$prioridade = $tarefa['prioridade'] ?? '';
$prazo = $tarefa['prazo'] ?? '';
$status = $tarefa['status'] ?? '';
$descricao = $tarefa['descricao'] ?? '';

curl_close($curl);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/Tarefas/formulario-principal.css">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <title>Editar Tarefa</title>
</head>
<body>
    <div class="container-adicionar-tarefa">
        <div class="adicionar-tarefa">
            <form id="formulario-adicionar-tarefa" method="POST" action="processos/actualizarTarefa.php?recurso=tarefa&id=<?= htmlspecialchars($tarefaId) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($tarefaId) ?>">

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Título da Tarefa" value="<?= htmlspecialchars($titulo) ?>">
                </div>

                <div class="form-group">
                    <label for="prioridade">Prioridade</label>
                    <select name="prioridade" id="prioridade">
                        <option value="" disabled <?= $prioridade == '' ? 'selected' : '' ?>>--Prioridade--</option>
                        <option value="baixa" <?= $prioridade == 'baixa' ? 'selected' : '' ?>>Baixa</option>
                        <option value="media" <?= $prioridade == 'media' ? 'selected' : '' ?>>Média</option>
                        <option value="alta" <?= $prioridade == 'alta' ? 'selected' : '' ?>>Alta</option>
                    </select>
                </div>

                <section id="prazo-etiquetas-section">
                    <p>Selecione o prazo</p>
                    <div id="etiquetas-prazo-container">
                        <div class="campo-data">
                            <label for="prazo">Prazo</label>
                            <input type="date" name="prazo" id="prazo" value="<?= htmlspecialchars($prazo) ?>">
                        </div>
                    </div>
                </section>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="" disabled <?= $status == '' ? 'selected' : '' ?>>--Selecione o status--</option>
                        <option value="pendente" <?= $status == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="em_andamento" <?= $status == 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" placeholder="Descrição"><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <div class="form-actions">
                    <input type="submit" value="Salvar Alterações" id="btn-adicionar">
                    <input type="button" value="Cancelar" id="btn-cancelar" onclick="window.location.href='../Menu.php';">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

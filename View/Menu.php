<?php
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
require_once __DIR__ ."/../api_core/configracao.php";

$token = $_COOKIE['tpwSSID'] ?? null;
$id     = $_GET['id'] ?? null;
$search = $_GET['pesquisa'] ?? null;

function requisitarAPI($url, $token)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_PROXY, '');
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


$urlTarefas = URL_BASE ."?recurso=tarefa";
if ($search) {
    $urlTarefas .= "&search=" . urlencode($search);
}

$urlEtiquetas = URL_BASE ."?recurso=etiqueta";

$listaDeTarefas = requisitarAPI($urlTarefas, $token);
$listaDeEtiquetas = requisitarAPI($urlEtiquetas, $token);

function listarEtiquetasNaTarefa($tarefaID, $listaDeEtiquetas)
{
    $html = '';
    if (isset($listaDeEtiquetas['dados'])) {
        foreach ($listaDeEtiquetas['dados'] as $etiqueta) {
            if (isset($etiqueta['tarefa_id']) && $etiqueta['tarefa_id'] == $tarefaID) {
                $html .= '
                <div class="etiqueta" style="background-color: ' . $etiqueta['cor'] . '; color: white;">
                    <span class="nome-etiqueta">' . $etiqueta['nome'] . '</span>
                </div>';
            }
        }
    }
    return $html;
}

function listarTarefas($listaDeTarefas, $listaDeEtiquetas)
{
    $html = '';

    $pendentes = [];
    $concluidas = [];

    foreach ($listaDeTarefas['dados'] as $tarefa) {
        if (isset($tarefa['status'])) {
            if ($tarefa['status'] === "pendente" || $tarefa['status'] === "em_andamento") {
                $pendentes[] = $tarefa;
            } elseif ($tarefa['status'] === "concluida") {
                $concluidas[] = $tarefa;
            }
        }
    }

    foreach ($pendentes as $tarefa) {
        $html .= renderizarTarefa($tarefa, $listaDeEtiquetas, false);
    }

    foreach ($concluidas as $tarefa) {
        $html .= renderizarTarefa($tarefa, $listaDeEtiquetas, true);
    }

    return $html;
}


function renderizarTarefa($tarefa, $listaDeEtiquetas, $concluida)
{

    $estiloDisplay = 'style="display:none;"';
    $estiloDescricao = $concluida ? 'style="text-decoration: line-through;"' : '';
    $checkboxChecked = $concluida ? 'checked' : '';
    $checkboxDisabled = $concluida ? 'style="pointer-events: none; cursor: not-allowed;"' : '';

    $statusLink = ($concluida)
        ? 'processos/definirComoEmAndamento.php?recurso=tarefa&id=' . $tarefa['id']
        : 'processos/definirComoConcluida.php?recurso=tarefa&id=' . $tarefa['id'];

    return '
        <div class="tarefa" id="tarefa_' . $tarefa['id'] . '" ' . $estiloDisplay . '>
            <div class="container-apenas-tarefas">
                <div class="checkbox-tarefa">
                    <form action="' . $statusLink . '" method="GET">
                        <input type="hidden" name="recurso" value="tarefa">
                        <input type="checkbox" 
                            id="concluirTarefas_' . $tarefa['id'] . '" 
                            name="tarefa" 
                            value="' . $tarefa['id'] . '" ' . $checkboxChecked . ' ' . $checkboxDisabled . ' 
                            onchange="this.form.submit()">
                    </form>
                </div>

                <label for="concluirTarefas_' . $tarefa['id'] . '" class="descricao" ' . $estiloDescricao . '>' . $tarefa['titulo'] . '</label>
                
                <div class="acoes">
                    <a href="processos/removertarefa.php?id=' . $tarefa['id'] . '&recurso=tarefa" class="remover">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    <a href="atualizarTarefa.php?id=' . $tarefa['id'] . '&recurso=tarefa" class="editar">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="visualizarTarefa.php?id=' . $tarefa['id'] . '&recurso=tarefa" class="visualizar">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="adicionarEtiqueta.php?id=' . $tarefa['id'] . '" class="detalhes">
                        <i class="fa-solid fa-ticket-simple"></i>
                    </a>
                </div>
            </div>
            <div class="container-etiqueta">' . listarEtiquetasNaTarefa($tarefa['id'], $listaDeEtiquetas) . '</div>
        </div>';
}

$htmlTarefas = listarTarefas($listaDeTarefas, $listaDeEtiquetas);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../style/Tarefas/tarefas.css">
    <link rel="stylesheet" href="../style/Tarefas/mesagens.css">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <link rel="stylesheet" href="../style/Tarefas/formulario-principal.css">
    <link rel="stylesheet" href="../style/Tarefas/visualizarTarefa.css">
    <link rel="stylesheet" href="fontawesome-free-7.0.0-web/css/all.min.css">
</head>

<body>
    <div class="fundo"></div>
    <div class="container">
        <aside>
            <h1>Tarefas</h1>
            <hr>
            <ul>
                <li><a href="Home.php">Perfil</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </aside>

        <main>
            <form id="Pesquisa" action="Menu.php" method="GET">
                <div class="search-container">
                    <span><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar..."
                        value="<?php echo $search ?? '' ?>">
                    <input type="hidden" name="recurso" value="tarefa">
                    <button id="btn-pesquisar" type="submit">Pesquisar</button>

                    <?php
                    if ($search) {
                        echo
                        '<a href="Menu.php?recurso=tarefa" class="limpar-pesquisa" style="margin-left: 10px; color: white;">
                        <i class="fa-solid fa-arrow-left"></i> Voltar
                        </a>';
                    };
                    ?>
                </div>
            </form>

            <form action=""></form>

            <form id="formulario-adicionar-titulo-tarefa">
                <div class="adicionar-tarefa-titulo">
                    <a href="adicionarTarefa.php" id="btnTituloDaTarefa" style="background:none; border:none; cursor:pointer; font-size:24px; color:#007bff;">
                            <span id="plus"><i class="fa-solid fa-circle-plus"></i></span>
                    </a>
                </div>
            </form>

            <!-- Listagem de tarefas -->
            <div class="container-tarefas"><?= $htmlTarefas ?></div>
        </main>

        <script type="text/javascript" src="../Javascript/Jquery/jquery-3.7.1.js"></script>
        <script type="text/javascript" src="../Javascript/Validate/jquery.validate.min.js"></script>
        <script type="text/javascript" src="../Javascript/Validate/messages_pt_PT.js"></script>
        <script>
            $(document).ready(function() {
                // Animação de fadeIn para as tarefas
                $('.tarefa').each(function(index) {
                    $(this).delay(index * 15).fadeIn(500);
                });
            });
        </script>
    </div>
</body>

</html>
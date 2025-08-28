<?php 
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../conexao.php";
require_once __DIR__ . "/../models/EtiquetaDAO.php";
require_once __DIR__ ."/../models/Objectos/Etiqueta.php";
require_once __DIR__ ."/../api_core/resposta.php";

Class EtiquetasController{

    public static function getEtiquetas($usuarioID){
        $etiquetaDAO = new EtiquetaDAO();
        try {
            $etiquetas = $etiquetaDAO->listaDeEstiquetas($usuarioID);
            echo Resposta::json(200, 'sucesso', $etiquetas);
        } catch (Exception $e) {
            error_log("Erro ao listar tarefas: ". $e->getMessage(), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao carregar etiquetas");
        }
    }

    public static function buscarEtiquetaPorId($tarefaID){

        $etiquetaDAO = new EtiquetaDAO();
        try {
            $listaDeEtiquetas = $etiquetaDAO->listaDeEtiquetasDeUmaTarefa($tarefaID);
            if ($listaDeEtiquetas) {
                echo Resposta::json(200, 'sucesso', $listaDeEtiquetas);
            } else {
                echo Resposta::json(405, "Erro ao buscar etiquetas");
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar etiquetas de uma tarefa: " . $e->getMessage(), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro no servidor ao listar etiquetas");
        }
    }
}

/*
$etiquetaDAO = new EtiquetaDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'ADICIONAR') {
    $listaDeEtiquetas = json_decode($_POST["listaDeEtiquetas"], true);

    try {
        foreach ($listaDeEtiquetas as $etiquetaDados) {
            $titulo = $etiquetaDados['nome'];
            $cor = $etiquetaDados['cor'];
            $tarefa = new Etiqueta(null ,$titulo, $cor, $usuarioID);

            if ($etiquetaDAO->adicionarEtiqueta($tarefa)) {
                echo json_encode(["resposta" => "sucesso"]);
            }
        }

    } catch (Exception $e) {
        echo json_encode(["resposta" => "erro", "mensagem" => $e->getMessage()]);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'ACTUALIZAR'){
    $listaDeEtiquetas = json_decode($_POST['listaDeEtiquetas'], true);

    if (count($listaDeEtiquetas) > 0) {
        foreach ($listaDeEtiquetas as $etiquetaDados) {
            $idEtiqueta = $etiquetaDados['etiqueta_id'];
            $nomeEtiqueta = trim($etiquetaDados['nome']);
            $corEtiqueta = trim($etiquetaDados['cor']);

            $etiqueta = new Etiqueta($idEtiqueta, $nomeEtiqueta, $corEtiqueta, $usuarioID);
            $idEtiqueta = $etiquetaDAO->actualizarEtiqueta($etiqueta);

            if (!$idEtiqueta) {
                echo json_encode(["resposta" => "erro", "mensagem" => "Falha ao adicionar etiqueta '$nomeEtiqueta'."]);
                exit();
            }
        }
    }
    echo json_encode(["resposta" => "sucesso"]);
}
*/
?>

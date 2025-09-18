<?php 
require_once __DIR__ . "/../conexao.php";
require_once __DIR__ . "/../models/EtiquetaDAO.php";
require_once __DIR__ ."/../models/Objectos/Etiqueta.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../models/Logger.php";

Class EtiquetasController{

    public static function getEtiquetas($usuarioID){
        $etiquetaDAO = new EtiquetaDAO();
        try {
            $etiquetas = $etiquetaDAO->listaDeEstiquetas($usuarioID);
            echo Resposta::json(200, 'sucesso', $etiquetas);
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao listar tarefas"), 3, __DIR__ . "/../Erro_log_per.log");
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
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro buscar etiqueta por ID"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro no servidor ao listar etiquetas");
        }
    }

    public static function adicionarEtiqueta($titulo, $cor, $usuarioID, $idTarefa) {
        try {
            $etiquetaDAO = new EtiquetaDAO();

            $idEtiqueta = $etiquetaDAO->buscarEtiquetaPorNomeCorUsuario($titulo, $cor, $usuarioID);
            if (!$idEtiqueta) {
                $etiqueta = new Etiqueta(null, $titulo, $cor, $usuarioID);
                $idEtiqueta = $etiquetaDAO->adicionarEtiqueta($etiqueta);

                if (!$idEtiqueta) {
                    echo Resposta::json(400, "Erro ao adicionar etiqueta");
                    exit();
                }
            }
            $etiquetaDAO->associarEtiquetaTarefa($idTarefa, $idEtiqueta);

            echo Resposta::json(200, "Etiqueta adicionada com sucesso");
            
        } catch (Exception $e) {
            error_log(Logger::exibirErro($e, "Erro adicionar tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro interno ao adicionar etiquetas");
        }
    }
}

/*
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
?>
*/

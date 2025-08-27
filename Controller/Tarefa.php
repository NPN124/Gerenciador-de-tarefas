<?php 
    require_once __DIR__ ."/../models/TarefaDAO.php";
    require_once __DIR__ ."/../models/EtiquetaDAO.php";
    require_once __DIR__ ."/../models/Objectos/Etiqueta.php";
    require_once __DIR__ ."/../models/Objectos/Tarefa.php";
    require_once __DIR__ ."/../api_core/resposta.php";
    require_once __DIR__ ."/../conexao.php";

    class TarefaController{

    public static function getTarefas($usuarioID)
    {
        try {

            $tarefaDAO = new TarefasDAO();
            $listaDeTarefas = $tarefaDAO->listarTarefas($usuarioID);

            if ($listaDeTarefas) {
                echo Resposta::json(200, 'sucesso', $listaDeTarefas);
            } else {
                echo Resposta::json(404, 'tarefa não encontrada');
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo Resposta::json(500, "erro ao comunicar com o servidor");
        }
    }

    public static function adicionarTarefa($dados, $usuarioID)
    {
        $tituloTarefa = $dados['titulo'];
        $prioridade = $dados["prioridade"];
        $status = $dados["status"];
        $descricao = $dados['descricao'];
        $prazo = $dados['prazo'];
        $listaDeEtiquetas = $dados["listaDeEtiquetas"] ?? [];

        try {
            $etiquetaDAO = new EtiquetaDAO();
            $tarefaDAO = new TarefasDAO();
            $tarefa = new Tarefa(null, $usuarioID, $tituloTarefa, $prazo, $prioridade, $status, $descricao);

            $idTarefa = $tarefaDAO->adicionarTarefa($tarefa);
            if (!$idTarefa) {
                echo Resposta::json(401, "Erro ao adicionar tarefa");
                exit();
            }

            if (is_array($listaDeEtiquetas) && count($listaDeEtiquetas) > 0) {

                foreach ($listaDeEtiquetas as $etiquetaDados) {
                    $nomeEtiqueta = trim($etiquetaDados['nome']);
                    $corEtiqueta = trim($etiquetaDados['cor']);
                    $idEtiqueta = $etiquetaDAO->buscarEtiquetaPorNomeCorUsuario($nomeEtiqueta, $corEtiqueta, $usuarioID);

                    if (!$idEtiqueta) {
                        $etiqueta = new Etiqueta(null, $nomeEtiqueta, $corEtiqueta, $usuarioID);
                        $idEtiqueta = $etiquetaDAO->adicionarEtiqueta($etiqueta);

                        if (!$idEtiqueta) {
                            echo Resposta::json(401, "Erro ao adicionar etiqueta");
                            exit();
                        }
                    }
                    $etiquetaDAO->associarEtiquetaTarefa($idTarefa, $idEtiqueta);
                }
            }

            echo Resposta::json(200);
            exit;
        } catch (Exception $e) {
            error_log("Erro ao adicionar tarefa: " .$e->getMessage());
            echo Resposta::json(500, "Erro ao adicionar tarefa");
            exit();
        }
    }
}


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'APAGAR') {

        try {
            $idDaTarefa = $_POST['id'];

            if ($tarefaDAO->removerTarefa($idDaTarefa)) {
                echo json_encode(["resposta" => "sucesso"]);
            } else {
                echo json_encode(["resposta" => "Erro ao remover tarefa"]);
            }
        } catch (\Throwable $e) {
            echo json_encode(["resposta" => "ERRO ao remover tarefa: {$e->getMessage()}"]);
        }
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'PESQUISAR') {

        try {
            $pesquisa = $_POST['pesquisa'];
            $tarefas = $tarefaDAO->pesquisarTarefas($pesquisa);

            if ($tarefas) {
                echo json_encode(["resposta" => "sucesso", "tarefas" => $tarefas]);
            } else {
                echo json_encode(["resposta" => "erro", "mensagem" => "Nenhuma tarefa encontrada"]);
            }
        } catch (\Throwable $e) {
            echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao pesquisar tarefas: " . $e->getMessage()]);
        }
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'ACTUALIZAR') {
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $descricao = $_POST['descricao'];
    $prazo = $_POST['prazo'];
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];

    $tarefa = new Tarefa($id, $usuarioID, $titulo, $prazo, $prioridade, $status, $descricao);

    try {
        if($tarefaDAO->atualizarTarefa($tarefa)){
            echo json_encode(["resposta" => "sucesso"]);
        } else {
            echo json_encode(["resposta" => "erro", "mensagem" => "Falha ao atualizar a tarefa"]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "resposta" => "erro",
            "mensagem" => "Erro inesperado ao atualizar tarefa: " . $e->getMessage()
        ]);
    }
    exit;
}
    
    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'BUSCAR') {
        $id = $_POST['id'];

        try {
            $tarefa = $tarefaDAO->buscarTarefaPorId($id);

            if ($tarefa) {
                echo json_encode(["resposta" => "sucesso", "tarefa" => $tarefa]);
            } else {
                echo json_encode(["resposta" => "erro", "mensagem" => "Tarefa não encontrada"]);
            }
        } catch (Exception $e) {
            echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao buscar tarefa: " . $e->getMessage()]);
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['acao'] == 'CONCLUIR') {
        $id = $_POST['id'];

        try {
            if($tarefaDAO->concluirTarefa($id)){
                echo json_encode(["resposta" => "sucesso"]);
            }else{
                echo json_encode(["resposta" => "erro", "mensagem" =>  "erro ao concluir a tarefa"]);
            }
        } catch (\Throwable $e) {
            echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao concluir a tarefa: " . $e->getMessage()]);
        }
    }
?>

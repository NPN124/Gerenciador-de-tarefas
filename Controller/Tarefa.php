<?php 
require_once __DIR__ ."/../models/Logger.php";
require_once __DIR__ ."/../models/TarefaDAO.php";
require_once __DIR__ ."/../models/EtiquetaDAO.php";
require_once __DIR__ ."/../models/Objectos/Etiqueta.php";
require_once __DIR__ ."/../models/Objectos/Tarefa.php";
require_once __DIR__ ."/../api_core/resposta.php";
require_once __DIR__ ."/../conexao.php";

class TarefaController {

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
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao carregar lista de tarefas"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "erro ao comunicar com o servidor");
        }
    }

    public static function adicionarTarefa($dados, $usuarioID)
    {
        $tituloTarefa = $dados['titulo'] ?? null;
        $prioridade   = $dados["prioridade"] ?? null;
        $status       = $dados["status"] ?? null;
        $descricao    = $dados['descricao'] ?? null;
        $prazo        = $dados['prazo'] ?? null;
        $listaDeEtiquetas = $dados["listaDeEtiquetas"] ?? [];

        try {
            $etiquetaDAO = new EtiquetaDAO();
            $tarefaDAO   = new TarefasDAO();
            $tarefa = new Tarefa(null, $usuarioID, $tituloTarefa, $prazo, $prioridade, $status, $descricao);

            $idTarefa = $tarefaDAO->adicionarTarefa($tarefa);
            if (!$idTarefa) {
                echo Resposta::json(400, "Erro ao adicionar tarefa");
                exit();
            }

            if (is_array($listaDeEtiquetas) && count($listaDeEtiquetas) > 0) {
                foreach ($listaDeEtiquetas as $etiquetaDados) {
                    $nomeEtiqueta = trim($etiquetaDados['nome']);
                    $corEtiqueta  = trim($etiquetaDados['cor']);

                    $idEtiqueta = $etiquetaDAO->buscarEtiquetaPorNomeCorUsuario($nomeEtiqueta, $corEtiqueta, $usuarioID);
                    if (!$idEtiqueta) {
                        $etiqueta = new Etiqueta(null, $nomeEtiqueta, $corEtiqueta, $usuarioID);
                        $idEtiqueta = $etiquetaDAO->adicionarEtiqueta($etiqueta);

                        if (!$idEtiqueta) {
                            echo Resposta::json(400, "Erro ao adicionar etiqueta");
                            exit();
                        }
                    }
                    $etiquetaDAO->associarEtiquetaTarefa($idTarefa, $idEtiqueta);
                }
            }

            echo Resposta::json(201, "Tarefa adicionada com sucesso", ["id" => $idTarefa]);
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e,"Erro ao adicionar tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao adicionar tarefa");
        }
    }

    public static function removerTarefa($idTarefa)
    {
        try {
            $tarefaDAO = new TarefasDAO();

            if ($tarefaDAO->removerTarefa($idTarefa)) {
                echo Resposta::json(200, "Tarefa removida com sucesso");
            } else {
                echo Resposta::json(500, "Erro ao remover tarefa");
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao remover tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao remover tarefa");
        }
    }

    public static function pesquisarTarefas($pesquisa)
    {
        try {
            $tarefaDAO = new TarefasDAO();
            $tarefas   = $tarefaDAO->pesquisarTarefas($pesquisa);

            if ($tarefas && count($tarefas) > 0) {
                echo Resposta::json(200, "sucesso", $tarefas);
            } else {
                echo Resposta::json(404, "Nenhuma tarefa encontrada");
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "erro ao pesquisar Tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao pesquisar tarefas");
        }
    }

    public static function atualizarTarefa($dados, $usuarioID)
    {
        $id         = $dados['id'] ?? null;
        $titulo = trim($dados['titulo']);
        $descricao  = $dados['descricao'] ?? null;
        $prazo      = $dados['prazo'] ?? null;
        $prioridade = $dados['prioridade'] ?? null;
        $status     = $dados['status'] ?? null;

        if (!$id || !$titulo || !$prazo || !$prioridade || !$status) {
            echo Resposta::json(400, "Campos obrigatórios não informados");
            exit;
        }

        $tarefa = new Tarefa($id, $usuarioID, $titulo, $prazo, $prioridade, $status, $descricao);

        try {
            $tarefaDAO = new TarefasDAO();

            if ($tarefaDAO->atualizarTarefa($tarefa)) {
                echo Resposta::json(200, "Tarefa atualizada com sucesso");
            } else {
                echo Resposta::json(500, "Falha ao atualizar a tarefa");
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "erro ao atualizar tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro inesperado ao atualizar tarefa");
        }
    }

    public static function buscarTarefaPorId($id)
    {
        try {
            $tarefaDAO = new TarefasDAO();
            $tarefa = $tarefaDAO->buscarTarefaPorId($id);

            if ($tarefa) {
                echo Resposta::json(200, "sucesso", $tarefa);
            } else {
                echo Resposta::json(404, "Tarefa não encontrada");
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao buscar Tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao buscar tarefa");
        }
    }

    public static function concluirTarefa($id)
    {
        try {
            $tarefaDAO = new TarefasDAO();

            if ($tarefaDAO->concluirTarefa($id)) {
                echo Resposta::json(200, "Tarefa concluída com sucesso");
            } else {
                echo Resposta::json(500, "Erro ao concluir a tarefa");
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao concluir tarefa"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao concluir a tarefa");
        }
    }
}
?>

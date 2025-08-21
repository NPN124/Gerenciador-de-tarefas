<?php 
    require_once __DIR__ ."/../models/TarefaDAO.php";
    require_once __DIR__ ."/../models/EtiquetaDAO.php";
    require_once __DIR__ ."/ClassEtiqueta.php";
    require_once __DIR__ ."/../conexao.php";

session_start();
$usuarioID = $_SESSION['usuario_id'];

class Tarefa {
    private $id, $usuarioID, $titulo, $prazo, $prioridade, $status, $descricao, $data_criacao, $data_conclusao;

    public function __construct($id, $usuarioID, $titulo, $prazo, $prioridade, $status, $descricao, $data_criacao = null, $data_conclusao = null) {
        $this->id = $id;
        $this->usuarioID = $usuarioID;
        $this->titulo = $titulo;
        $this->prazo = $prazo;
        $this->descricao = $descricao;
        $this->prioridade = $prioridade;
        $this->status = $status ?? "media";
        $this->data_criacao = $data_criacao ?? date('Y-m-d H:i:s');
        $this->data_conclusao = $data_conclusao;
    }

    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getPrazo() { return $this->prazo; }
    public function getDescricao() { return $this->descricao; }
    public function getPrioridade() { return $this->prioridade; }
    public function getStatus() { return $this->status; }
    public function getDataCriacao() { return $this->data_criacao; }
    public function getDataConclusao() { return $this->data_conclusao; }
    public function getUsuarioID() { return $this->usuarioID; }

    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setPrazo($prazo) { $this->prazo = $prazo; }
    public function setPrioridade($prioridade) { $this->prioridade = $prioridade; }
    public function setStatus($status) { $this->status = $status; }
    public function setDataCriacao($data_criacao) { $this->data_criacao = $data_criacao; }
    public function setDataConclusao($data_conclusao) { $this->data_conclusao = $data_conclusao; }
    public function setUsuarioID($usuarioID) { $this->usuarioID = $usuarioID; }
}

    $tarefaDAO = new TarefasDAO();
    $etiquetaDAO = new EtiquetaDAO();

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        try {
            $listaDeTarefas = $tarefaDAO->listarTarefas($usuarioID);

            if ($listaDeTarefas) {
                echo json_encode($listaDeTarefas);
            } else {
                echo json_encode(["resposta" => "erro"]);
            }
        } catch (PDOException $e) {
            error_log("Erro ao buscar tarefas: " . $e->getMessage());
            echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao buscar tarefas"]);
        }
        exit();
    }

    
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'ADICIONAR') {
    $tituloTarefa = trim($_POST["titulo"]);
    $prioridade = $_POST["prioridade"];
    $status = $_POST["status"];
    $descricao = $_POST['descricao'];
    $prazo = $_POST['prazo'];
    $listaDeEtiquetas = json_decode($_POST["listaDeEtiquetas"] ?? '[]', true);

    $tarefa = new Tarefa(null, $usuarioID, $tituloTarefa, $prazo, $prioridade, $status, $descricao);

    try {
        $idTarefa = $tarefaDAO->adicionarTarefa($tarefa);

        if (!$idTarefa) {
            echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao adicionar tarefa."]);
            exit();
        }

        if (is_array($listaDeEtiquetas) && count($listaDeEtiquetas) > 0) {

            foreach ($listaDeEtiquetas as $etiquetaDados) {
                $nomeEtiqueta = trim($etiquetaDados['nome']);
                $corEtiqueta = trim($etiquetaDados['cor']);
                $idEtiqueta = $etiquetaDAO->buscarEtiquetaPorNomeCorUsuario($nomeEtiqueta, $corEtiqueta, $usuarioID);

                if (!$idEtiqueta) {
                    $etiqueta = new Etiqueta(null ,$nomeEtiqueta, $corEtiqueta, $usuarioID);
                    $idEtiqueta = $etiquetaDAO->adicionarEtiqueta($etiqueta);

                    if (!$idEtiqueta) {
                        echo json_encode(["resposta" => "erro", "mensagem" => "Falha ao adicionar etiqueta '$nomeEtiqueta'."]);
                        exit();
                    }
                }
                $etiquetaDAO->associarEtiquetaTarefa($idTarefa, $idEtiqueta);
            }
        }

        echo json_encode(["resposta" => "sucesso"]);
        exit();

    } catch (Exception $e) {
        echo json_encode(["resposta" => "erro", "mensagem" => "Erro ao adicionar tarefa e etiquetas: " . $e->getMessage()]);
        exit();
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
<?php 
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../conexao.php";
require_once __DIR__ . "/../models/EtiquetaDAO.php";

session_start();
$usuarioID = $_SESSION['usuario_id'];

class Etiqueta {

    private $id;
    private $nome;
    private $cor;
    private $usuarioId;

    public function __construct($id, $nome, $cor, $usuarioId) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cor = $cor;
        $this->usuarioId = $usuarioId;
    }

    public function getID() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCor() {
        return $this->cor;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }
}

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


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $etiquetas = $etiquetaDAO->listaDeEstiquetas($usuarioID);
        echo json_encode(["resposta" => "sucesso", "etiquetas" => $etiquetas]);
    } catch (Exception $e) {
        echo json_encode(["resposta" => "erro"]);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'BUSCAR'){
    $tarefaID = $_POST['tarefaID'];

    $listaDeEtiquetasDaTarefa = $etiquetaDAO->listaDeEtiquetasDeUmaTarefa($tarefaID);

    if($listaDeEtiquetasDaTarefa){
        echo json_encode(["resposta" => "sucesso", "etiquetas" => $listaDeEtiquetasDaTarefa]);
    }else{
        echo json_encode(["resposta" => "erro"]);
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

?>
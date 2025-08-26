<?php 
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
?>
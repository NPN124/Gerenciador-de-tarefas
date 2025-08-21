<?php 
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
?>
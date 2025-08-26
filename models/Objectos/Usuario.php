<?php 
    class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $tipo_usuario;
    private $status;
    private $criado_em;

    public function __construct($id, $nome, $email, $senha, $tipo_usuario = null, $status = null, $criado_em = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->tipo_usuario = $tipo_usuario ? $tipo_usuario : 'usuario';
        $this->status = $status ? $status : 'ativo';
        $this->criado_em = $criado_em ? $criado_em :date('Y-m-d H:i:s');
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getTipoUsuario() {
        return $this->tipo_usuario;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCriadoEm() {
        return $this->criado_em;
    }

    

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setTipoUsuario($tipo_usuario) {
        $this->tipo_usuario = $tipo_usuario;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>
<?php 
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/SessaoDAO.php';
require_once __DIR__ . '/../Controller/Sessao.php';

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

class UsuarioMetodos {

    public static function verificarUsuario($email, $senha){
        $usuarios = UsuarioDAO::listaDeUsuarios();

        foreach ($usuarios as $usuario) {
            if ($usuario->getEmail() == $email && password_verify($senha, $usuario->getSenha())) {
                $sessao = new Sessao(
                    null,
                    $usuario->getId(),
                    $_SERVER['HTTP_USER_AGENT'],
                    self::getToken(),
                    true,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s')
                );
                $_COOKIE['token'] = $sessao->getToken();
                setcookie("tpwSSID", $sessao->getToken(), time() + (86400 * 30), "/");
                SessaoDAO::registarSessao($sessao);

                session_start();
                $_SESSION['usuario_id'] = $usuario->getId();
                $_SESSION['usuario_nome'] = $usuario->getNome();
                $_SESSION['usuario_email'] = $usuario->getEmail();
                $_SESSION['usuario_tipo'] = $usuario->getTipoUsuario();
                $_SESSION['usuario_status'] = $usuario->getStatus();
                $_SESSION['sessao_token'] = $sessao->getToken();
                $_SESSION['sessao_id'] = $sessao->getId();
                return true;
            }
        }
        return false;
    }
    
    public static function getToken(){
        return uniqid('token_', true);
    } 
}    
?>

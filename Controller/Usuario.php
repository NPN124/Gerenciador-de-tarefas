<?php 
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/SessaoDAO.php';
require_once __DIR__ . '/../models/Objectos/Sessao.php';
require_once __DIR__ ."/../models/Objectos/Usuario.php";

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

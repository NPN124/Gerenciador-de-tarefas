<?php 
require_once __DIR__ . '/../models/Logger.php';
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../models/SessaoDAO.php';
require_once __DIR__ . '/../models/Objectos/Sessao.php';
require_once __DIR__ ."/../models/Objectos/Usuario.php";
require_once __DIR__ ."/../models/UsuarioDAO.php";

class UsuarioController {

    public static function cadastrarUsuario($nome, $email, $senha, $tipo = 'usuario')
    {
        try {
            if (UsuarioDAO::verificarSeUsuarioExiste($email)) {
                echo Resposta::json(409, "Usuário já cadastrado. Por favor, tente outro email.");
                exit;
            }

            if (UsuarioDAO::adicionarUsuario($nome, $email, $senha, $tipo)) {
                echo Resposta::json(201, "Usuário cadastrado com sucesso.");
                exit;
            }
        } catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro ao cadastrar Usuario"), 3, __DIR__ . "/../Erro_log_per.log");
            echo Resposta::json(500, "Erro ao cadastrar usuário.");
            exit;
        }
    }


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

<?php
require_once __DIR__ . '/../Conexao.php';

class SessaoDAO
{

    public static function registarSessao($Sessao)
    {
        $sql = "INSERT INTO sessoes (token, isValid, usuario_id, user_agent, data_login, ultimo_acesso) 
                    VALUES (:token, :isValid, :usuario_id, :user_agent, :data_login, :ultimo_acesso)";
        $pdo = DBConnection::getInstance();
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':token', $Sessao->getToken());
        $statement->bindValue(':isValid', $Sessao->getIsValid());
        $statement->bindValue(':usuario_id', $Sessao->getUsuarioId());
        $statement->bindValue(':user_agent', $Sessao->getUserAgent());
        $statement->bindValue(':data_login', $Sessao->getDataLogin());
        $statement->bindValue(':ultimo_acesso', $Sessao->getUltimoAcesso());
        return $statement->execute();
    }


    public static function atualizarSessao($sessao)
    {
        $sql = "UPDATE sessoes SET 
                    isValid = :isValid,
                    user_agent = :user_agent,
                    data_login = :data_login,
                    ultimo_acesso = :ultimo_acesso
                WHERE token = :token";
        $pdo = DBConnection::getInstance();
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':isValid', $sessao->getIsValid());
        $statement->bindValue(':user_agent', $sessao->getUserAgent());
        $statement->bindValue(':data_login', $sessao->getDataLogin());
        $statement->bindValue(':ultimo_acesso', $sessao->getUltimoAcesso());
        $statement->bindValue(':token', $sessao->getToken());
        return $statement->execute();
    }
}

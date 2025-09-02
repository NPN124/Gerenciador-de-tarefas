<?php
require_once __DIR__ . '/../Conexao.php';
require_once __DIR__ . '/../Controller/Usuario.php';

class UsuarioDAO {

    public static function adicionarUsuario($nome, $email, $senha, $tipo = 'usuario') {
        $pdo = DBConnection::getInstance();
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $tipo_usuario = $tipo;
        $status = null;
        $criado_em = null;

        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario, status, criado_em)
         VALUES (:nome, :email, :senha, :tipo_usuario, :status, :criado_em)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':criado_em', $criado_em);
        return $stmt->execute();
    }

    public static function listaDeUsuarios() {
        $pdo = DBConnection::getInstance();
        $usuarios = [];
        $sql = "SELECT * FROM usuarios";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new Usuario(
                $row['id'],
                $row['nome'],
                $row['email'],
                $row['senha'],
                $row['tipo_usuario'],
                $row['status'],
                $row['criado_em']
            );
        }
        return $usuarios;
    }

    public static function buscarUsuarioPorEmail($email) {
        $pdo = DBConnection::getInstance();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Usuario(
                $row['id'],
                $row['nome'],
                $row['email'],
                $row['senha'],
                $row['tipo_usuario'],
                $row['status'],
                $row['criado_em']
            );
        }
        return null;
    }

    public static function verificarSeUsuarioExiste($email) {
        $pdo = DBConnection::getInstance();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
}
?>
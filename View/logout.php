<?php
session_start();
require_once __DIR__ .'/../conexao.php';

if (isset($_COOKIE['tpwSSID'])) {

    $dataLogaut = date('Y-m-d H:i:s');
    $token = $_COOKIE['tpwSSID'];

    try {
        $pdo = DBConnection::getInstance();
        $sql = "UPDATE sessoes SET isValid = 0, ultimo_acesso = :ultimo_acesso WHERE token = :token";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':ultimo_acesso', $dataLogaut);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Erro ao atualizar isValid: " . $e->getMessage());
    }
    
    session_unset();
    session_destroy();

    header("Location: ../index.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>


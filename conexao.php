<?php
class DBConnection {

    public static function getInstance() {
        $host = 'localhost';
        $db   = 'BD_tarefas';
        $user = 'root';
        $pass = '123';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Falha na conexão: " . $e->getMessage());
        }
    }
}
?>

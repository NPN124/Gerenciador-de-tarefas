<?php
require_once __DIR__ . '/models/Logger.php';
class DBConnection {

    public static function getInstance() {
        $host = 'localhost';
        $db   = 'BD_tarefas';
        $user = 'root';
        $pass = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log(Logger::exibirErro($e, "Erro de conexão com o banco de dados"), 3, __DIR__ . "/Erro_log_per.log");
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }catch (Throwable $e) {
            error_log(Logger::exibirErro($e, "Erro inesperado ao conectar ao banco de dados"), 3, __DIR__ . "/Erro_log_per.log");
            die("Erro inesperado ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
?>

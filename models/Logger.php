<?php
class Logger {
    public static function exibirErro(PDOException|Exception|Throwable $e, $textoExtra = "Erro") {
        $erroCompleto = "\n==================== ERRO ====================\n"
            . $textoExtra . ":\n"
            . "Mensagem: " . $e->getMessage() . "\n"
            . "Código: " . $e->getCode() . "\n"
            . "Arquivo: " . $e->getFile() . "\n"
            . "Linha: " . $e->getLine() . "\n"
            . "Stack trace:\n" . $e->getTraceAsString() . "\n"
            . "===============================================\n\n";

        return $erroCompleto;
    }
}
?>
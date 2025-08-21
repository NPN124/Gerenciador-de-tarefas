<?php
require_once __DIR__ . "/../conexao.php";

class EtiquetaDAO
{

    private $tabela = 'etiquetas';

    public function adicionarEtiqueta($etiqueta)
    {
        try {
            $pdo = DBConnection::getInstance();
            $sql = "INSERT INTO {$this->tabela} (nome, cor, usuario_id) VALUES (:nome, :cor, :usuario_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $etiqueta->getNome());
            $stmt->bindValue(':cor', $etiqueta->getCor());
            $stmt->bindValue(':usuario_id', $etiqueta->getUsuarioId());
            if($stmt->execute()){
                return $pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao adicionar etiqueta na base de dados: " . $e->getMessage());
        }
    }

    public function actualizarEtiqueta($etiqueta){

        try{
            $pdo = DBConnection::getInstance();
            $sql = "UPDATE " . $this->tabela . " SET nome = :nome, cor = :cor WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $etiqueta->getNome());
            $stmt->bindValue('cor', $etiqueta->getCor());
            $stmt->bindValue(':id', $etiqueta->getID());
            return $stmt->execute();
        } catch(PDOException $e){
            throw new Exception("Erro ao apagar tarefa na base de dados: " .$e->getMessage());
        }
    }

    public function listaDeEstiquetas($usuarioID) {
        try {
            $pdo = DBConnection::getInstance();
            $sql = "SELECT 
                e.id AS etiqueta_id,
                e.nome AS nome,
                e.cor AS cor,
                e.usuario_id AS usuario_id,
                te.tarefa_id AS tarefa_id
            FROM etiquetas e
            INNER JOIN tarefa_etiqueta te ON e.id = te.etiqueta_id
            WHERE e.usuario_id = :usuario_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuarioID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar etiquetas: " . $e->getMessage());
            return [];
        }
    }

    public function listaDeEtiquetasDeUmaTarefa($tarefaId) {
        try {
            $pdo = DBConnection::getInstance();
            $sql = "SELECT 
                        e.id AS etiqueta_id,
                        e.nome AS nome,
                        e.cor AS cor
                    FROM etiquetas e
                    INNER JOIN tarefa_etiqueta te ON e.id = te.etiqueta_id
                    WHERE te.tarefa_id = :tarefaId";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':tarefaId', $tarefaId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar etiquetas da tarefa: " . $e->getMessage());
        }
    }

    public function associarEtiquetaTarefa($tarefaId, $etiquetaId)
    {
        try {
            $pdo = DBConnection::getInstance();
            $sql = "INSERT INTO tarefa_etiqueta (tarefa_id, etiqueta_id) VALUES (:tarefa_id, :etiqueta_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':tarefa_id', $tarefaId);
            $stmt->bindValue(':etiqueta_id', $etiquetaId);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao associar etiqueta à tarefa: " . $e->getMessage());
        }
    }

    public function buscarEtiquetaPorNomeCorUsuario($nome, $cor, $usuarioId) {
        try {
            $pdo = DBConnection::getInstance();
            $sql = "SELECT id FROM etiquetas WHERE nome = :nome AND cor = :cor AND usuario_id = :usuario_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':cor', $cor);
            $stmt->bindValue(':usuario_id', $usuarioId);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && isset($resultado['id'])) {
                return $resultado['id'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar etiqueta: " . $e->getMessage());
        }
    }
}



<?php 
require_once __DIR__ . '/../Conexao.php';
require_once __DIR__ . '/../Controller/Tarefa.php';


    class TarefasDAO {

        private $tabela = "tarefas";

        public function adicionarTarefa($tarefa){
            try {
                $pdo = DBConnection::getInstance();

                if (empty($tarefa->getTitulo()) || empty($tarefa->getPrazo())) {
                    throw new Exception("Título e Prazo são obrigatórios.");
                }

                $sql = "INSERT INTO " . $this->tabela . " (titulo, descricao, prioridade, status, prazo, criado_em, atualizado_em, usuario_id) 
                                                    VALUES (:titulo, :descricao, :prioridade, :status, :prazo, :criado_em, :atualizado_em, :usuario_id)";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':titulo', $tarefa->getTitulo());
                $statement->bindValue(':descricao', $tarefa->getDescricao());
                $statement->bindValue(':prioridade', $tarefa->getPrioridade());
                $statement->bindValue(':status', $tarefa->getStatus());
                $statement->bindValue(':prazo', $tarefa->getPrazo());
                $statement->bindValue(':criado_em', $tarefa->getDataCriacao());
                $statement->bindValue(':atualizado_em', $tarefa->getDataConclusao());
                $statement->bindValue(':usuario_id', $tarefa->getUsuarioID());
                if ($statement->execute()) {
                    return $pdo->lastInsertId();
                }
                return false;
            } catch (PDOException $e) {
                throw new Exception("Erro ao adicionar tarefa: " . $e->getMessage());
            }
        }

        public function concluirTarefa($tarefaID){
            try {
                $pdo = DBConnection::getInstance();
                $sql = "UPDATE {$this->tabela} SET status = 'concluida' WHERE id = :tarefaID";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':tarefaID', $tarefaID);
                return $statement->execute();
            } catch (Exception $e) {
                throw new Exception("Erro ao concluir a tarefa: " .$e->getMessage());
            }
        }

        public function listarTarefas($usuarioID) {
            try {
                $pdo = DBConnection::getInstance();
                $sql = "SELECT * FROM {$this->tabela} WHERE usuario_id = :usuarioID";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':usuarioID', $usuarioID);
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Erro ao listar tarefas: " . $e->getMessage());
            }
        }

        public function removerTarefa($id){
            $pdo = DBConnection::getInstance();
            $sql = "DELETE FROM " . $this->tabela . " WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function pesquisarTarefas($valor){
            try {
                $pdo = DBConnection::getInstance();
                $sql = "SELECT * FROM " . $this->tabela . " WHERE titulo LIKE :palavra";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':palavra', $valor . '%');
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Erro ao pesquisar tarefas: " . $e->getMessage());
            }
        }

        public function buscarTarefaPorId($id) {
            try {
                $pdo = DBConnection::getInstance();
                $sql = "SELECT * FROM " . $this->tabela . " WHERE id = :id";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':id', $id);
                $statement->execute();
                return $statement->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Erro ao buscar tarefa por ID: " . $e->getMessage());
            }
        }

        public function atualizarTarefa($tarefa) {
            try {
                $pdo = DBConnection::getInstance();
                $sql = "UPDATE " . $this->tabela . " SET 
                            titulo = :titulo, 
                            descricao = :descricao, 
                            prioridade = :prioridade, 
                            status = :status
                        WHERE id = :id";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':id', $tarefa->getId());
                $statement->bindValue(':titulo', $tarefa->getTitulo());
                $statement->bindValue(':descricao', $tarefa->getDescricao());
                $statement->bindValue(':prioridade', $tarefa->getPrioridade());
                $statement->bindValue(':status', $tarefa->getStatus());
                return $statement->execute();
            } catch (PDOException $e) {
                echo "Erro ao atualizar tarefa: " . $e->getMessage();
            }
        }
    }
?>
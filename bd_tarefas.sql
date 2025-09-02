-- 1. Banco de dados
CREATE DATABASE bd_tarefas;
USE bd_tarefas;

DROP DATABASE bd_tarefas;

SELECT * from tarefas;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    tipo_usuario ENUM('usuario', 'admin') DEFAULT 'usuario',
    status ENUM('ativo', 'bloqueado') DEFAULT 'ativo',
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (id ,nome, email, senha, tipo_usuario, status)
VALUES 
(2, 'Naran', 'naran@gmail.com', '$2y$10$VQeCDl1v2DjpSG6OCL3Nq.1CQWUvHiSgWuauFdFJvUQVPuR9jXyZm', 'usuario', 'ativo'),
(3, 'Administrador', 'admin@gmail.com', '$2y$10$VQeCDl1v2DjpSG6OCL3Nq.1CQWUvHiSgWuauFdFJvUQVPuR9jXyZm', 'admin', 'ativo');



CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    prioridade ENUM('baixa', 'media', 'alta') DEFAULT 'media',
    status ENUM('pendente', 'em_andamento', 'concluida') DEFAULT 'em_andamento',
    prazo DATE,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tarefas usuário 2
INSERT INTO tarefas (titulo, descricao, prioridade, status, prazo, usuario_id) VALUES
('Revisar contrato de prestação de serviço', 'Verificar cláusulas referentes à rescisão e prazos de pagamento.', 'alta', 'pendente', '2025-08-10', 2),
('Enviar relatório mensal ao cliente', 'Gerar PDF com dados de desempenho e enviar por e-mail.', 'media', 'em_andamento', '2025-08-12', 2),
('Backup do servidor de produção', 'Realizar cópia completa e salvar em nuvem segura.', 'alta', 'pendente', '2025-08-14', 2),
('Atualizar documentação técnica', 'Adicionar endpoints recentes da API e exemplos de uso.', 'media', 'em_andamento', '2025-08-15', 2),
('Corrigir bug na tela de login', 'Erro de autenticação quando senha contém caracteres especiais.', 'alta', 'pendente', '2025-08-16', 2),
('Treinamento da equipe de suporte', 'Preparar apresentação e repassar procedimentos atualizados.', 'media', 'pendente', '2025-08-17', 2),
('Reunião com fornecedor de software', 'Negociar renovação do contrato de licenciamento.', 'baixa', 'pendente', '2025-08-18', 2),
('Testar integração com sistema de pagamentos', 'Simular pagamentos com diferentes bandeiras e cenários.', 'alta', 'em_andamento', '2025-08-19', 2),
('Publicar release no blog da empresa', 'Texto sobre nova funcionalidade lançada nesta semana.', 'media', 'concluida', '2025-08-05', 2),
('Analisar desempenho do site', 'Verificar Google Analytics e ajustar SEO conforme necessário.', 'baixa', 'concluida', '2025-08-01', 2),
('Planejar campanha de marketing', 'Definir público-alvo e canais de divulgação para agosto.', 'media', 'pendente', '2025-08-20', 2),
('Atualizar cadastro de clientes', 'Corrigir e-mails inválidos e dados desatualizados.', 'baixa', 'em_andamento', '2025-08-21', 2),
('Fazer auditoria interna de segurança', 'Verificar permissões, acessos e autenticação.', 'alta', 'pendente', '2025-08-22', 2),
('Criar protótipo da nova interface', 'Usar Figma para desenhar tela de dashboard.', 'media', 'em_andamento', '2025-08-23', 2),
('Agendar manutenção da rede', 'Coordenar com TI para evitar impacto nos usuários.', 'baixa', 'pendente', '2025-08-24', 2),
('Verificar pendências no Jira', 'Encerrar tarefas antigas e atribuir responsáveis.', 'media', 'pendente', '2025-08-25', 2),
('Apresentação para diretoria', 'Revisar slides e treinar discurso de 10 minutos.', 'alta', 'pendente', '2025-08-26', 2),
('Realizar testes de usabilidade', 'Convidar 3 usuários reais para feedback.', 'media', 'em_andamento', '2025-08-27', 2),
('Organizar pastas do Google Drive', 'Mover arquivos antigos para pastas de arquivo morto.', 'baixa', 'concluida', '2025-08-02', 2),
('Enviar nota fiscal ao cliente', 'Conferir dados e emitir via sistema da contabilidade.', 'media', 'pendente', '2025-08-28', 2);

-- Tarefas usuário 3 (20 tarefas)
INSERT INTO tarefas (titulo, descricao, prioridade, status, prazo, usuario_id) VALUES
('Revisar políticas internas', 'Atualizar regras de conduta e compliance.', 'alta', 'pendente', '2025-09-01', 3),
('Planejar treinamento de equipe', 'Preparar material e cronograma para treinamento.', 'media', 'em_andamento', '2025-09-02', 3),
('Atualizar planilhas financeiras', 'Revisar despesas e receitas do mês anterior.', 'alta', 'pendente', '2025-09-03', 3),
('Testar sistema de backup', 'Garantir que backups automáticos estão funcionando.', 'baixa', 'pendente', '2025-09-04', 3),
('Criar relatório de vendas', 'Gerar relatório semanal para diretoria.', 'media', 'em_andamento', '2025-09-05', 3),
('Auditar permissões do sistema', 'Revisar acessos de usuários no ERP.', 'alta', 'pendente', '2025-09-06', 3),
('Organizar arquivos digitais', 'Reestruturar pastas de documentos antigos.', 'baixa', 'concluida', '2025-09-07', 3),
('Atualizar website institucional', 'Adicionar novas seções e corrigir links.', 'media', 'em_andamento', '2025-09-08', 3),
('Responder e-mails de clientes', 'Tratar solicitações e dúvidas pendentes.', 'alta', 'pendente', '2025-09-09', 3),
('Agendar reunião de equipe', 'Definir horário e pauta da reunião semanal.', 'baixa', 'concluida', '2025-09-10', 3),
('Revisar contrato fornecedor', 'Verificar cláusulas e prazos de renovação.', 'alta', 'pendente', '2025-09-11', 3),
('Planejar campanha marketing', 'Definir público-alvo e canais de divulgação.', 'media', 'em_andamento', '2025-09-12', 3),
('Atualizar cadastro clientes', 'Corrigir e-mails inválidos e dados desatualizados.', 'baixa', 'concluida', '2025-09-13', 3),
('Fazer auditoria interna', 'Verificar acessos e autenticação de sistemas.', 'alta', 'pendente', '2025-09-14', 3),
('Criar protótipo UI', 'Desenhar interface do novo dashboard.', 'media', 'em_andamento', '2025-09-15', 3),
('Agendar manutenção TI', 'Coordenar com equipe para minimizar impacto.', 'baixa', 'pendente', '2025-09-16', 3),
('Revisar pendências Jira', 'Encerrar tarefas antigas e atribuir responsáveis.', 'media', 'pendente', '2025-09-17', 3),
('Apresentação diretoria', 'Revisar slides e treinar discurso de 10 minutos.', 'alta', 'pendente', '2025-09-18', 3),
('Testes de usabilidade', 'Convidar 3 usuários reais para feedback.', 'media', 'em_andamento', '2025-09-19', 3),
('Organizar arquivos Google Drive', 'Mover arquivos antigos para arquivo morto.', 'baixa', 'concluida', '2025-09-20', 3);


CREATE TABLE etiquetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cor VARCHAR(7),
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);


INSERT INTO etiquetas (nome, cor, usuario_id) VALUES
('Urgente', '#FF0000', 2),
('Revisão', '#00FF00', 2),
('Planejamento', '#0000FF', 2),
('Importante', '#FFA500', 2),
('Concluído', '#808080', 2),
('Financeiro', '#8B0000', 2),
('TI', '#2E8B57', 2),
('Marketing', '#FF69B4', 2);


INSERT INTO etiquetas (nome, cor, usuario_id) VALUES
('Urgente', '#FF0000', 3),
('Revisão', '#00FF00', 3),
('Planejamento', '#0000FF', 3),
('Importante', '#FFA500', 3),
('Concluído', '#808080', 3),
('Financeiro', '#8B0000', 3),
('TI', '#2E8B57', 3),
('Marketing', '#FF69B4', 3);


CREATE TABLE tarefa_etiqueta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarefa_id INT,
    etiqueta_id INT,
    FOREIGN KEY (tarefa_id) REFERENCES tarefas(id) ON DELETE CASCADE,
    FOREIGN KEY (etiqueta_id) REFERENCES etiquetas(id)
);

-- Relacionando tarefas do usuário 2 com etiquetas do usuário 2
-- Tarefas do usuário 2 com múltiplas etiquetas
INSERT INTO tarefa_etiqueta (tarefa_id, etiqueta_id) VALUES
(1, 1), (1, 2), (1, 4),            -- Tarefa 1: Urgente, Revisão, Importante
(2, 3), (2, 4), (2, 7),            -- Tarefa 2: Planejamento, Importante, TI
(3, 1), (3, 6),                     -- Tarefa 3: Urgente, Financeiro
(4, 2), (4, 3), (4, 4),             -- Tarefa 4: Revisão, Planejamento, Importante
(5, 1), (5, 2),                     -- Tarefa 5: Urgente, Revisão
(6, 3), (6, 7),                     -- Tarefa 6: Planejamento, TI
(7, 4), (7, 7),                     -- Tarefa 7: Importante, TI
(8, 1), (8, 7),                     -- Tarefa 8: Urgente, TI
(9, 5), (9, 3),                     -- Tarefa 9: Concluído, Planejamento
(10, 2), (10, 6),                   -- Tarefa 10: Revisão, Financeiro
(11, 3), (11, 4),                   -- Tarefa 11: Planejamento, Importante
(12, 6), (12, 3),                   -- Tarefa 12: Financeiro, Planejamento
(13, 1), (13, 4),                   -- Tarefa 13: Urgente, Importante
(14, 3), (14, 7),                   -- Tarefa 14: Planejamento, TI
(15, 7), (15, 4),                   -- Tarefa 15: TI, Importante
(16, 3), (16, 2),                   -- Tarefa 16: Planejamento, Revisão
(17, 4), (17, 1),                   -- Tarefa 17: Importante, Urgente
(18, 2), (18, 3),                   -- Tarefa 18: Revisão, Planejamento
(19, 5), (19, 6),                   -- Tarefa 19: Concluído, Financeiro
(20, 6), (20, 3);                   -- Tarefa 20: Financeiro, Planejamento

-- Tarefas do usuário 3 com múltiplas etiquetas
INSERT INTO tarefa_etiqueta (tarefa_id, etiqueta_id) VALUES
(21, 9), (21, 10), (21, 12),        -- Tarefa 21: Urgente, Revisão, Planejamento
(22, 11), (22, 12),                  -- Tarefa 22: Planejamento, Importante
(23, 9), (23, 14),                   -- Tarefa 23: Urgente, Financeiro
(24, 13), (24, 15),                  -- Tarefa 24: Baixa, TI
(25, 11), (25, 12),                  -- Tarefa 25: Planejamento, Importante
(26, 9), (26, 13),                   -- Tarefa 26: Urgente, Financeiro
(27, 14), (27, 15),                  -- Tarefa 27: Concluído, TI
(28, 11), (28, 12),                  -- Tarefa 28: Planejamento, Importante
(29, 9), (29, 10),                   -- Tarefa 29: Urgente, Revisão
(30, 14), (30, 15),                  -- Tarefa 30: Baixa, Concluído
(31, 9), (31, 12),                   -- Tarefa 31: Urgente, Importante
(32, 11), (32, 12),                  -- Tarefa 32: Planejamento, Importante
(33, 14), (33, 15),                  -- Tarefa 33: Concluído, TI
(34, 9), (34, 13),                   -- Tarefa 34: Urgente, Financeiro
(35, 11), (35, 12),                  -- Tarefa 35: Planejamento, Importante
(36, 14), (36, 15),                  -- Tarefa 36: Baixa, TI
(37, 9), (37, 12),                   -- Tarefa 37: Urgente, Importante
(38, 11), (38, 12),                  -- Tarefa 38: Planejamento, Importante
(39, 14), (39, 15),                  -- Tarefa 39: Concluído, TI
(40, 13), (40, 11);                  -- Tarefa 40: Financeiro, Planejamento



SELECT * from tarefa_etiqueta;



CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT,
    tarefa_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (tarefa_id) REFERENCES tarefas(id)
);

CREATE TABLE logs_acoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE sessoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    user_agent VARCHAR(255),
    isValid BOOLEAN,
    token VARCHAR(255) UNIQUE,
    data_login DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

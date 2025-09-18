<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/Tarefas/formulario-principal.css">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <title>Adicionar Tarefa</title>
</head>

<body>
    <div class="container-adicionar-tarefa">
        <div class="adicionar-tarefa">
            <form id="formulario-adicionar-tarefa">

                <div class="form-group">
                    <label for="titulo"></label>
                    <input type="text" name="titulo" id="titulo" placeholder="Título da Tarefa">
                </div>

                <div class="form-group">
                    <label for="prioridade"></label>
                    <select name="prioridade" id="prioridade">
                        <option value="" selected disabled>--Prioridade--</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>

                <section id="prazo-etiquetas-section">
                    <p>Selecione o prazo</p>
                    <div id="etiquetas-prazo-container">
                        <div class="campo-data">
                            <label for="prazo"></label>
                            <input type="date" name="prazo" id="prazo">
                        </div>
                    </div>
                </section>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="" selected disabled>--Selecione o status--</option>
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em andamento</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descricao"></label>
                    <textarea id="descricao" name="descricao" placeholder="Descrição"></textarea>
                </div>
                <div class="form-actions">
                    <input type="button" value="Adicionar Tarefa" id="btn-adicionar">
                    <input type="button" value="Cancelar" id="btn-cancelar">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
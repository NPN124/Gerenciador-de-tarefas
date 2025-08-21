<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["tituloDaTarefa"];

    if ($titulo && trim($titulo) !== '') {
        echo json_encode(["resposta" => "sucesso"]);
    } else {
        echo json_encode(["resposta" => "erro"]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../style/Tarefas/tarefas.css">
    <link rel="stylesheet" href="../style//Tarefas/mesagens.css">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <link rel="stylesheet" href="../style/Tarefas/formulario-principal.css">
    <link rel="stylesheet" href="../style/Tarefas/visualizarTarefa.css">
    <link rel="stylesheet" href="fontawesome-free-7.0.0-web/css/all.min.css">
</head>

<body>
    <div class="fundo"></div>
    <div class="container">
        <aside>
            <h1>Tarefas</h1>
            <hr>
            <ul>
                <li><a href="Home.php">Perfil</a></li>
                <li><a href="../index.php">Logout</a></li>
            </ul>
        </aside>

        <main>
            <form id="Pesquisa">
                <div class="search-container">
                    <span><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar...">
                </div>
            </form>

            <form id="formulario-adicionar-titulo-tarefa">
                <div class="adicionar-tarefa-titulo">
                    <input type="text" name="tituloDaTarefa" id="tituloDaTarefa" placeholder="Adicionar nova tarefa...">
                    <button type="submit" id="btnTituloDaTarefa" style="background:none; border:none; cursor:pointer; font-size:24px; color:#007bff;">
                        <span id="plus"><i class="fa-solid fa-circle-plus"></i></span>
                    </button>
                </div>
            </form>

            <!-- Listagem de tarefas -->
            <div class="container-tarefas"></div>

        </main>

        <!-- Formulário para adicionar/editar tarefa -->
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
                            <div class="campo-botao">
                                <button id="btn-formulario-adicionar-etiqueta" type="button">
                                    Adicionar Etiqueta
                                </button>
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
                        <input type="button" value="Atualizar Tarefa" id="btn-atualizar" data-id="" style="display:none;">
                        <input type="button" value="Cancelar" id="btn-cancelar">
                    </div>
                </form>
            </div>
        </div>

        <!-- Adicionar-Etiqueta -->
        <div id="adicionar-etiqueta">
            <div>
                <label for="nomeDaEtiqueta">Digite o nome da etiqueta</label>
                <input type="text" id="nomeDaEtiqueta" placeholder="Nome da Etiqueta">
            </div>
            <div>
                <label for="corDaEtiqueta">Escolha a cor da etiqueta</label>
                <input type="color" id="corDaEtiqueta" value="#000000">
            </div>
            <div id="botoes-etiqueta">
                <button id="btn-confirmar-etiquetas">Confirmar</button>
                <button id="btn-executarEditar-etiqueta">Actualizar</button>
                <button id="btn-adicionar-etiqueta">Adicionar</button>
                <button id="btn-cancelar-etiqueta">Cancelar</button>
            </div>
            <div id="lista-de-etiquetas"></div>
        </div>

        <!-- Visualizar Tarefa -->
        <dialog id="modal-visualizar" class="modal-container">
            <div class="visualizar-tarefa">
                <h2 id="titulo-visualizacao">Título da tarefa</h2>
                
                <div class="tarefa-detalhes">
                    <p><strong>Prioridade:</strong> <span id="prioridade-visualizacao"></span></p>
                    <p><strong>Status:</strong> <span id="status-visualizacao"></span></p>
                    <p><strong>Prazo:</strong> <span id="prazo-visualizacao"></span></p>
                </div>
                
                <div class="descricao-container">
                    <p><strong>Descrição:</strong></p>
                    <p id="descricao-visualizacao"></p>
                </div>

                <div class="form-actions">
                    <button id="btn-fechar-visualizacao" class="btn-fechar">Fechar</button>
                </div>
            </div>
        </dialog>

        <script type="text/javascript" src="../Javascript/Jquery/jquery-3.7.1.js"></script>
        <script type="text/javascript" src="../Javascript/Validate/validat.js"></script>
        <script type="text/javascript" src="../Javascript/Tarefas.js"></script>
        <script type="text/javascript" src="../Javascript/Efeitos.js"></script>
        <script type="text/javascript" src="../Javascript/Etiqueta.js"></script>
        <script type="text/javascript" src="../Javascript/visualizar.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/Tarefas/etiqueta.css">
    <title>Adicionar Etiqueta</title>
</head>
<body>
    <div id="adicionar-etiqueta">
        <form action="processos/adicionarEtiqueta.php?idTarefa=<?php echo $_GET['id']?>" method="POST">
            <div>
                <label for="nomeDaEtiqueta">Digite o nome da etiqueta</label>
                <input type="text" id="nome" name="nome" placeholder="Nome da Etiqueta" required>
            </div>
            <div>
                <label for="corDaEtiqueta">Escolha a cor da etiqueta</label>
                <input type="color" id="cor" name="cor">
            </div>
            <div id="botoes-etiqueta">
                <button type="submit" id="btn-adicionar-etiqueta">Adicionar</button>
                <a href="../View/Menu.php">Sair</a>
            </div>
        </form>
    </div>
</body>
</html>
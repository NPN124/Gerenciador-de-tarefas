<?php
include_once(__DIR__ . "/../conexao.php");
require_once __DIR__ . '/../models/UsuarioDAO.php';

session_start();
$tipo_usuario = $_SESSION['usuario_tipo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $tipo_usuario = $_POST["tipo_usuario"] ?? 'usuario';

    if (UsuarioDAO::verificarSeUsuarioExiste($email)) {
        $mensagem = "Usuario já cadastrado. Por favor, tente outro email.";
    } else {
        if (!empty($nome) && !empty($email) && !empty($senha)) {
            UsuarioDAO::adicionarUsuario($nome, $email, $senha, $tipo_usuario);
        } else {
            echo "<script>alert('Preencha todos os campos!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link rel="stylesheet" href="../style/Usuario/cadastroUsuario.css">
</head>

<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form id="cadastro" method="post">

            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <div class="input-group">
                <?php if ($tipo_usuario == "admin") { ?>
                    <select name="tipo_usuario" id="tipo_usuario" required>
                        <option value="" disabled selected hidden>Selecione o tipo de usuário</option>
                        <option value="usuario">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                    <label for="tipo_usuario">Tipo de Usuário</label>
                <?php } else { ?>
                <?php } ?>
            </div>

            <span><?php echo $mensagem ?? " " ?></span>

            <div class="btns">
                <button type="submit" class="buttons">Adicionar</button>
                <button type="reset" class="buttons">Limpar</button>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="../Javascript/Jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="../Javascript/Validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../Javascript/Validate/messages_pt_PT.js"></script>
    <script>
        $(document).ready(function() {
            $("#cadastro").validate({
                rules: {
                    nome: {
                        required: true

                    },
                    email: {
                        required: true

                    },
                    senha: {
                        required: true

                    }
                },
                messages: {
                    nome: "Por favor, insira seu nome",
                    email: "Por favor, insira um email válido",
                    senha: "Por favor, insira a senha"
                }
            });
        });
    </script>
</body>

</html>
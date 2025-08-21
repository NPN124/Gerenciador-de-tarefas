<?php
include_once(__DIR__ . "/Controller/Usuario.php");
include_once(__DIR__ . "/models/UsuarioDAO.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if (UsuarioMetodos::verificarUsuario($email, $senha)) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "Email ou senha incorretos."]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/Usuario/login.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body>
    <div class="container">
        <h1>Login</h1>
        <form id="Login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="" placeholder=" " required>
            </div>

            <div class="form-group">
                <label id="labelSenha" for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                <span><a href="View/cadastroUsuario.php">Não tem uma conta?</a></span>
                    <span class="error-message"></span>
            </div>
            <div class="form-group">
                <input type="submit" value="Entrar">
            </div>
        </form>
    </div>
    <script type="text/javascript" src="javascript/Validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="javascript/Validate/messages_pt_PT.js"></script>
    <script>
        $("#Login").validate({
            rules:{
                email:{
                    required: true,
                    maxlength: 100,
                    
                },
                senha:{
                    required: true,
                }
            }
        })

        $('#Login').submit(function(event) {
            event.preventDefault();

            const email = $('#email').val();
            const senha = $('#senha').val();

            $.ajax({
                type: 'POST',
                url: 'index.php',
                data: {
                    email: email,
                    senha: senha
                },
                dataType: 'json'
            }).done(function(result) {
                if (result.status == "ok") {
                    window.location.href = "View/Home.php";
                } else {
                    $('.error-message').text(result.mensagem);
                }
            });
        });
    </script>
</body>
</html>
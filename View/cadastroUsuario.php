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

            <span class="mensagem"></span>

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
        $("#cadastro").on("submit", function(e) {
            e.preventDefault();

            const dados = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                senha: $("#senha").val()
            };

            $.ajax({
                url: "../api_core/cURL/cURL.php?recurso=usuario",
                type: "POST",
                data: JSON.stringify(dados),
                dataType: "json"
            }).done(function(resultado) {
                if (resultado.status == 201) {
                    alert(resultado.mensagem);
                    $(".menagem").text(resultado.mensagem);
                    window.location.href = "../index.php";
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Erro:", textStatus, errorThrown);
            });
        });
    </script>
</body>

</html>
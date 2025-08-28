$('#btn-cancelar-etiqueta').on('click', function () {
    cancelarAdicionarEtiqueta();
});

$('#btn-confirmar-etiquetas').on('click', function () {
    mostrarFundo(true, 1);
    mostrarDIV_2($('#adicionar-etiqueta'), false);
    $('#btn-formulario-adicionar-etiqueta').text("Etiqueta adicionada");
    $("#nomeEtiqueta").val("");
});

let etiquetas = [];
let indexEdicao = null;

function cancelarAdicionarEtiqueta() {
    etiquetas = [];
    $("#nomeDaEtiqueta").val("");
    $("#corDaEtiqueta").val("");
    mostrarFundo(true, 1);
    mostrarDIV_2($('#adicionar-etiqueta'), false);
    $('#btn-formulario-adicionar-etiqueta').text("Adicionar Etiqueta");
};
function listarEtiquetasNaTarefa($tarefaID, conteiner) {
    $.ajax({
        url: '../api_core/cURL/cURL.php/?recurso=etiqueta',
        method: 'GET',
        dataType: 'json'
    }).done(function (resultado) {
        if (resultado.status == 200) {
            const etiquetas = resultado.dados;
            for (let i = 0; i < etiquetas.length; i++) {
                const etiqueta = etiquetas[i];
                if(etiqueta.tarefa_id == $tarefaID){
                    const elemento = $(`
                    <div class="etiqueta" style="background-color: ${etiqueta.cor}; color: white;">
                        <span class="nome-etiqueta">${etiqueta.nome}</span>
                    </div>
                `);
                    conteiner.append(elemento);
                }
            }
        } else {
            console.log("Erro ao listar etiquetas");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText)
    });
}

function buscarEtiquetas(tarefaID) {
    $.ajax({
        type: 'GET',
        url: `../api_core/cURL/cURL.php/?recurso=etiqueta&id=${tarefaID}`,
        dataType: 'json',
    }).done(function(resultado){
        if(resultado.status == 200){
            console.log(resultado.dados);
            etiquetas = resultado.dados; 
            renderizarEtiquetas();          
        } else {
            console.log(resultado.mensagem);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function renderizarEtiquetas() {
    const lista = $("#lista-de-etiquetas");
    lista.empty();

    for (let i = 0; i < etiquetas.length; i++) {
        lista.append(`
            <div class="etiqueta" data-nome="${etiquetas[i].nome}" data-cor="${etiquetas[i].cor}">
                <button class="editar-etiqueta" data-index="${i}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <span class="nome-da-etiqueta">${etiquetas[i].nome}</span>
                <button class="remover-etiqueta" data-index="${i}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `);
    }
}


$("#btn-adicionar-etiqueta").on("click", function () {
    const nome = $("#nomeDaEtiqueta").val().trim();
    const cor = $("#corDaEtiqueta").val();

    if (!nome) {
        alert("Digite um nome para a etiqueta!");
        return;
    }

    etiquetas.push({ nome, cor });
    renderizarEtiquetas();
    $("#nomeDaEtiqueta").val("");
});


$("#lista-de-etiquetas").on("click", ".remover-etiqueta", function () {
    const index = $(this).data("index");
    etiquetas.splice(index, 1);
    renderizarEtiquetas();
});


$("#lista-de-etiquetas").on("click", ".editar-etiqueta", function() {
    indexEdicao = $(this).data("index");
    const etiqueta = etiquetas[indexEdicao];

    $("#nomeDaEtiqueta").val(etiqueta.nome);
    $("#corDaEtiqueta").val(etiqueta.cor);

    $("#btn-executarEditar-etiqueta").show();
    $("#btn-adicionar-etiqueta").hide();
});


$("#btn-executarEditar-etiqueta").click(function() {
    const novoNome = $("#nomeDaEtiqueta").val().trim();
    const novaCor = $("#corDaEtiqueta").val();

    if (!novoNome) return alert("Digite o nome da etiqueta.");

    etiquetas[indexEdicao] = {
    etiqueta_id: etiquetas[indexEdicao].etiqueta_id, // mantém o id da etiqueta
    nome: novoNome,
    cor: novaCor
    };

    renderizarEtiquetas();

    indexEdicao = null;
    $("#btn-executarEditar-etiqueta").hide();
    $("#btn-adicionar-etiqueta").show();

    $("#nomeDaEtiqueta").val("");
    $("#corDaEtiqueta").val("#000000");
});

/*
function adicionarEtiqueta() {
    const listaDeEtiquetas = etiquetas;

    if (listaDeEtiquetas.length == 0) {
        return;
    }

    $.ajax({
        url: "../Controller/Etiqueta.php",
        type: "POST",
        data: {
            acao: "ADICIONAR",
            listaDeEtiquetas: JSON.stringify(listaDeEtiquetas)
        },
        dataType: "json"
    })
    .done(function (resultado) {
        if (resultado.resposta == "sucesso") {
            console.log("Etiqueta adicionada com sucesso: " + resultado.mensagem);
        } else {
            console.log("Erro ao adicionar etiqueta: " + resultado.mensagem);
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Erro na requisição AJAX:", textStatus, errorThrown, jqXHR);
        alert("Erro ao enviar etiquetas. Tente novamente.");
    });
}

function actualizarEtiquetas(){
    const listaDeEtiquetas = etiquetas;

    console.log(listaDeEtiquetas);

    if(listaDeEtiquetas.length <= 0){
        return;
    }

    $.ajax({
        url: '../Controller/Etiqueta.php',
        method: 'POST',
        data: {
            acao: 'ACTUALIZAR',
            listaDeEtiquetas: JSON.stringify(listaDeEtiquetas)
        },
        dataType: 'json'
    }).done(function(resultado){
        if(resultado.resposta == "sucesso"){
            console.log("Tarefas actualizadas com sucesso");
        }else{
            console.log(resultado.mensagem);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });

    etiquetas = [];
}
*/

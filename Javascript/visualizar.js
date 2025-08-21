$('.container-tarefas').on('click', '.visualizar', function () {
    console.log("mostrando");
    carregarVisualizar($(this).data("id"));
});

$('#btn-fechar-visualizacao').on('click', function () {
    mostrarFundo(false);
    mostrarDIV($('#modal-visualizar'), false);
});

function carregarVisualizar(tarefaID) {
    mostrarFundo(true);
    mostrarDIV($('#modal-visualizar'), true);

    $('#titulo-visualizacao').text("Carregando...");
    $('#prioridade-visualizacao').text("");
    $('#prazo-visualizacao').text("");
    $('#status-visualizacao').text("");
    $('#descricao-visualizacao').text("");

    buscarEtiquetas(tarefaID);

    $.ajax({
        type: 'POST',
        url: '../Controller/Tarefa.php',
        data: {
            acao: 'BUSCAR',
            id: tarefaID
        },
        dataType: 'json'
    }).done(function (resultado) {
        if (resultado.resposta === 'sucesso' && resultado.tarefa) {
            $('#titulo-visualizacao').text(resultado.tarefa.titulo);
            $('#prioridade-visualizacao').text(resultado.tarefa.prioridade);
            $('#prazo-visualizacao').text(resultado.tarefa.prazo);
            $('#status-visualizacao').text(resultado.tarefa.status);
            $('#descricao-visualizacao').text(resultado.tarefa.descricao);
        } else {
            $('#titulo-visualizacao').text("Tarefa não encontrada.");
        }
    }).fail(function () {
        $('#titulo-visualizacao').text("Erro ao carregar tarefa.");
    });
}

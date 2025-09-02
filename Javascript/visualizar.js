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

    $.ajax({
        type: 'GET',
        url: `../api_core/cURL/cURL.php?recurso=tarefa&id=${tarefaID}`,
        dataType: 'json'
    }).done(function (resultado) {
        if (resultado.status == 200) {

            const tarefa = resultado.dados;
            $('#titulo-visualizacao').text(tarefa.titulo);
            $('#prioridade-visualizacao').text(tarefa.prioridade);
            $('#prazo-visualizacao').text(tarefa.prazo);
            $('#status-visualizacao').text(tarefa.status);
            $('#descricao-visualizacao').text(tarefa.descricao);
        } else {
            $('#titulo-visualizacao').text("Tarefa não encontrada.");
        }
    }).fail(function () {
        $('#titulo-visualizacao').text("Erro ao carregar tarefa.");
    });
}

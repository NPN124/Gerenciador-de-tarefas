$('#formulario-adicionar-titulo-tarefa').submit(adicionarTituloTarefa)
$('#btn-adicionar').on('click', adicionarTarefa);

$('#btn-cancelar').on('click', cancelarTarefa);

$('.container-tarefas').on('click', '.editar', function(){
    const tarefaID = $(this).data("id");
    escolherTarefaAEditar(tarefaID);
    buscarEtiquetas(tarefaID);
    $(".campo-botao").css({
        display: 'none'
    });

});


$('.container-tarefas').on('change', '#concluirTarefas', function() {
    const tarefaID = $(this).data("id");
    const elemento = $(this).closest('.tarefa').find('.descricao');

    elemento.css('text-decoration', 'line-through');
    $(this).prop('checked', true);

    $(this).css({
        'pointer-events': 'none',
    });

    concluirTarefa(tarefaID);
});


$('#btn-atualizar').on('click', atualizarTarefa);

$('.container-tarefas').on('click', '.remover', removerTarefa);

$('#pesquisa').on('input', PesquisarTarefaPeloTitulo);

$('#btn-formulario-adicionar-etiqueta').on('click', function () {
    mostrarFundo(true, 3);
    mostrarDIV_2($('#adicionar-etiqueta'), true);
});

$(document).ready(function () {
    console.log("tua mae");
    listarTarefas();
});


function PesquisarTarefaPeloTitulo() {
    const valor = $('#pesquisa').val();
    console.log("Valor atual:", valor);

    $.ajax({
        type: 'GET',
        url: `../api_core/cURL/cURL.php/?recurso=tarefa&search=${valor}`,
        dataType: 'json'
    }).done(function (resultado) {
        console.log(resultado.tarefas);
        if (resultado.status == 200) {
            listarTarefasEspecificas(resultado.dados);
        } else {
            console.log(resultado.mensage);
        }
    })
}


function adicionarTituloTarefa(event) {
    event.preventDefault();

    const tituloDaTarefa = $('#tituloDaTarefa').val().trim();

    $.ajax({
        type: 'POST',
        url: '../View/Menu.php',
        data: {
            tituloDaTarefa: tituloDaTarefa
        },
        dataType: 'json',
    }).done(function (resultado) {
        if (resultado.resposta == 'sucesso') {
            const div = $('.container-adicionar-tarefa');
            $('#titulo').val(tituloDaTarefa);
        }
    });
    mostrarFundo(true);
    mostrarDIV($('.container-adicionar-tarefa'), true);
}

function adicionarTarefa() {

    $('#btn-adicionar').css({ display: 'block' });
    $('#btn-atualizar').css({ display: 'none' });

    const titulo = $('#titulo').val();
    const prioridade = $('#prioridade').val();
    const prazo = $('#prazo').val();
    const status = $('#status').val();
    const descricao = $('#descricao').val();

    const listaDeEtiquetas = etiquetas;

    if (!titulo || !prazo) {
        alert("Preencha todos os campos!");
        $('#btn-adicionar').prop('disabled', false);
        return;
    }

    let tarefa_etiquetas = {
        titulo: titulo,
        prioridade: prioridade,
        prazo: prazo,
        status: status,
        descricao: descricao
    };

    if (listaDeEtiquetas && listaDeEtiquetas.length > 0) {
        tarefa_etiquetas.listaDeEtiquetas = JSON.stringify(listaDeEtiquetas);
    }

    $.ajax({
        type: 'POST',
        url: '../api_core/cURL/cURL.php/?recurso=tarefa',
        data: JSON.stringify(tarefa_etiquetas),
        dataType: 'json'
    }).done(function (resultado) {
        $('#btn-adicionar').prop('disabled', false);
        console.log(resultado);
        if (resultado.status == 200) {
            listarTarefas();
        } else {
            console.log("Erro ao adicionar tarefa");
            console.log(resultado.mensagem || resultado.message);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });

    $('#tituloDaTarefa').val('');
    $('#titulo').val('');
    $('#prioridade').prop('selectedIndex', 0);
    $('#prazo').val('');
    $('#btn-formulario-adicionar-etiqueta').text("Adicionar Etiqueta");
    $('#status').prop('selectedIndex', 0);
    $('#descricao').val('');

    mostrarFundo(false);
    mostrarDIV($('.container-adicionar-tarefa'), false);
    etiquetas = [];
    $("#lista-de-etiquetas").empty();
            $(".campo-botao").css({
        display: 'block'
    });
}



function cancelarTarefa() {
    mostrarFundo(false);
    mostrarDIV($('.container-adicionar-tarefa'), false);

    $('#tituloDaTarefa').val('');
    $('#titulo').val('');
    $('#prioridade').prop('selectedIndex', 0);
    $('#prazo').val('');
    $('#status').prop('selectedIndex', 0);
    $('#descricao').val('');
    etiquetas = [];

    $('#btn-adicionar').css({
        display: 'block'
    });
    $('#btn-atualizar').css({
        display: 'none'
    });
        $(".campo-botao").css({
        display: 'block'
    });

}

function escolherTarefaAEditar(tarefa_id) {

    mostrarFundo(true);
    mostrarDIV($('.container-adicionar-tarefa'), true);

    $('#btn-adicionar').css({display: 'none'});
    $('#btn-atualizar').css({display: 'block'});

    const tarefaID = tarefa_id;

    $('#btn-atualizar').data("id", tarefaID);

    console.log("ID da tarefa a ser editada:", tarefaID);
    buscarEtiquetas(tarefaID);

    $.ajax({
        type: 'POST',
        url: '../Controller/Tarefa.php',
        data: {
            acao: 'BUSCAR',
            id: tarefaID
        },
        dataType: 'json',
    }).done(function (resultado) {
        console.log(resultado);
        if (resultado.resposta == 'sucesso') {
            console.log("Tarefa encontrada:", resultado.tarefa);
            $('#titulo').val(resultado.tarefa.titulo);
            $('#prioridade').val(resultado.tarefa.prioridade);
            $('#prazo').val(resultado.tarefa.prazo);
            $('#status').val(resultado.tarefa.status);
            $('#descricao').val(resultado.tarefa.descricao);
        }
    });
}

function atualizarTarefa() {
    console.log("Atualizando tarefa...");

    const tarefaID = $('#btn-atualizar').data('id');
    const titulo = $('#titulo').val();
    const prioridade = $('#prioridade').val();
    const prazo = $('#prazo').val();
    const status = $('#status').val();
    const descricao = $('#descricao').val();

    console.log("ID da tarefa:", tarefaID);

    let tarefa_etiquetas = {
        acao: 'ACTUALIZAR',
        id: tarefaID,
        titulo: titulo,
        prioridade: prioridade,
        prazo: prazo,
        status: status,
        descricao: descricao,
    };

    actualizarEtiquetas();

    $.ajax({
        type: 'POST',
        url: '../Controller/Tarefa.php',
        data: tarefa_etiquetas,
        dataType: 'json',
    }).done(function (resultado) {
        $('btn-adicionar').prop('disabled', false);
        if (resultado.resposta == 'sucesso') {
            listarTarefas();
            alert("Tarefa atualizada com sucesso");
        } else {
            console.log("resposta:", resultado.message);
        }

        $('#tituloDaTarefa').val('');
        $('#titulo').val('');
        $('#prioridade').prop('selectedIndex', 0);
        $('#prazo').val('');
        $('#status').prop('selectedIndex', 0);
        $('#descricao').val('');

        console.log("Tarefa atualizada com sucesso");

        $('.container-adicionar-tarefa').slideUp(200);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });
    mostrarFundo(false);
    mostrarDIV($('.container-adicionar-tarefa'), false);
}

function concluirTarefa(tarefaID) {

    const idTarefa = tarefaID;

    $.ajax({
        url: '../Controller/Tarefa.php',
        method: 'POST',
        data: {
            acao: "CONCLUIR", 
            id: idTarefa 
        },
        dataType: 'json'
    })
        .done(function (resultado) {
            if (resultado.resposta === "sucesso") {
                console.log("Tarefa concluída");
            } else {
                console.log("Falha ao concluir tarefa");
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erro AJAX: ", textStatus, errorThrown, jqXHR);
        });

}

function removerTarefa() {


    if (!confirm("Tem certeza que deseja apagar a tarefa?")) {
        return;
    };

    const tarefaID = $(".remover").data('id');

    $.ajax({
        type: 'POST',
        url: '../Controller/Tarefa.php',
        data: {
            acao: 'APAGAR',
            id: tarefaID
        },
        dataType: 'json',
    }).done(function (resultado) {
        if (resultado.resposta == 'sucesso') {
            listarTarefas();
            $('#pesquisa').val('');
            console.log("Tarefa removida com sucesso");
        } else {
            console.log("Erro ao remover tarefa");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });
}


function listarTarefas() {
    $('.container-tarefas').empty();

    $.ajax({
        url: '../api_core/cURL/cURL.php/?recurso=tarefa',
        method: 'GET',
        dataType: 'json'
    }).done(function (resultado) {

        console.log(resultado);
        const tarefas = resultado.dados;
        const pendentes = tarefas.filter(t => t.status === "pendente" || t.status === "em_andamento");
        const concluidas = tarefas.filter(t => t.status === "concluida");
           
        function renderizar(lista){
            for (let i = 0; i < lista.length; i++) {
                    const tarefa = lista[i];

                    const elemento = $(`
                    <div class="tarefa" style="display:none;">
                        <div class="container-apenas-tarefas">
                            <div class="checkbox-tarefa">
                                <input type="checkbox" id="concluirTarefas" name="tarefa${i}" data-id="${tarefa.id}"/>
                            </div>
                            <label for="tarefa${i}" class="descricao">${tarefa.titulo}</label>
                            <div class="acoes">
                                <button type="button" class="remover" data-id="${tarefa.id}">
                                   <i class="fa-solid fa-trash"></i>
                                </button>
                                <button type="button" class="editar" data-id="${tarefa.id}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="visualizar" data-id="${tarefa.id}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="container-etiqueta"></div>
                    </div>
                `);

                if (tarefa.status === "concluida") {
                    elemento.find('.descricao').css('text-decoration', 'line-through');
                    const checkbox = elemento.find('#concluirTarefas');
                    checkbox.prop('checked', true);
                    checkbox.css({
                        'pointer-events': 'none',
                        'cursor': 'not-allowed'
                    });

                    elemento.find('.acoes button').prop('disabled', true).css({
                        'cursor': 'not-allowed',
                    });
                }
                $('.container-tarefas').append(elemento);

                setTimeout(function () {
                    elemento.fadeIn(500);
                }, i * 15);

                const containerEtiquetas = elemento.find(".container-etiqueta");
                listarEtiquetasNaTarefa(tarefa.id, containerEtiquetas);
            }
        }
        renderizar(pendentes);
        renderizar(concluidas);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("errrooooooo");
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
    });
}

function listarTarefasEspecificas(tarefasArray) {

    $('.container-tarefas').empty();

    for (let i = 0; i < tarefasArray.length; i++) {
        const tarefa = tarefasArray[i];

        const elemento = $(`
            <div class="tarefa" style="display:none;">
                <div class="container-apenas-tarefas">
                    <div class="checkbox-tarefa">
                        <input type="checkbox" id="concluirTarefas" name="tarefa${i}" />
                    </div>
                    <label for="tarefa${i}" class="descricao">${tarefa.titulo}</label>
                    <div class="acoes">
                        <button type="button" class="remover" data-id="${tarefa.id}">
                            <i class="fa-solid fa-trash remover"></i>
                        </button>
                        <button type="button" class="editar" data-id="${tarefa.id}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button type="button" class="visualizar" data-id="${tarefa.id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="container-etiqueta"></div>
            </div>
        `);

        if (tarefa.status === "concluida") {
            elemento.find('.descricao').css('text-decoration', 'line-through');
            const checkbox = elemento.find('#concluirTarefas');
            checkbox.prop('checked', true);
            checkbox.css({
                'pointer-events': 'none',
                'cursor': 'not-allowed'
            });
        }


        $('.container-tarefas').append(elemento);
        elemento.fadeIn(300);

        const containerEtiquetas = elemento.find(".container-etiqueta");
        listarEtiquetasNaTarefa(tarefa.id, containerEtiquetas);
    }
}



















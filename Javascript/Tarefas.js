$('#formulario-adicionar-titulo-tarefa').submit(adicionarTituloTarefa)
$('#btn-adicionar').on('click', adicionarTarefa);

$('#btn-cancelar').on('click', cancelarTarefa);

$('.container-tarefas').on('click', '.editar', function(){
    const tarefaID = $(this).data("id");
    escolherTarefaAEditar(tarefaID);
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

$('.container-tarefas').on('click', '.remover', function(){
        const tarefaID = $(this).data("id");
        removerTarefa(tarefaID);
});

$('#pesquisa').on('input', PesquisarTarefaPeloTitulo);

$('#btn-formulario-adicionar-etiqueta').on('click', function () {
    mostrarFundo(true, 3);
    mostrarDIV_2($('#adicionar-etiqueta'), true);
});

$(document).ready(function () {
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
    mostrarFundo(true);
    mostrarDIV($('.container-adicionar-tarefa'), true);
    $('#titulo').val(tituloDaTarefa);
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
        tarefa_etiquetas.listaDeEtiquetas = listaDeEtiquetas;
    }

    $.ajax({
        type: 'POST',
        url: '../api_core/cURL/cURL.php/?recurso=tarefa',
        data: JSON.stringify(tarefa_etiquetas),
        dataType: 'json'
    }).done(function (resultado) {
        $('#btn-adicionar').prop('disabled', false);
        if (resultado.status == 201) {
            listarTarefas();
        } else {
            console.log(resultado.mensagem);
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
    $.ajax({
        type: 'GET',
        url: `../api_core/cURL/cURL.php/?recurso=tarefa&id=${tarefaID}`,
        dataType: 'json',
    }).done(function (resultado) {
        console.log(resultado);
        if (resultado.status == 200) {
            const tarefa = resultado.dados;
            console.log("Tarefa encontrada:", tarefa.tarefa);
            $('#titulo').val(tarefa.titulo);
            $('#prioridade').val(tarefa.prioridade);
            $('#prazo').val(tarefa.prazo);
            $('#status').val(tarefa.status);
            $('#descricao').val(tarefa.descricao);
        }
    });
}


function atualizarTarefa() {
    $('#btn-adicionar').css({ display: 'none' });
    $('#btn-atualizar').css({ display: 'block' });

    const tarefaID = $('#btn-atualizar').data('id');
    const titulo = $('#titulo').val();
    const prioridade = $('#prioridade').val();
    const prazo = $('#prazo').val();
    const status = $('#status').val();
    const descricao = $('#descricao').val();

    const listaDeEtiquetas = etiquetas;

    if (!titulo || !prazo) {
        alert("Preencha todos os campos obrigatórios!");
        $('#btn-atualizar').prop('disabled', false);
        return;
    }

    let tarefa_etiquetas = {
        id: tarefaID,
        titulo: titulo,
        prioridade: prioridade,
        prazo: prazo,
        status: status,
        descricao: descricao
    };

    if (listaDeEtiquetas && listaDeEtiquetas.length > 0) {
        tarefa_etiquetas.listaDeEtiquetas = listaDeEtiquetas;
    }

    $.ajax({
        type: 'PUT',
        url: '../api_core/cURL/cURL.php/?recurso=tarefa',
        data: JSON.stringify(tarefa_etiquetas),
        contentType: 'application/json',  // 🔑 importante para enviar JSON corretamente
        dataType: 'json'
    }).done(function (resultado) {
        $('#btn-atualizar').prop('disabled', false);
        console.log(resultado);

        if (resultado.status == 200) {
            listarTarefas();
            alert("Tarefa atualizada com sucesso!");

            // ✅ Resetar campos apenas se deu sucesso
            $('#tituloDaTarefa').val('');
            $('#titulo').val('');
            $('#prioridade').prop('selectedIndex', 0);
            $('#prazo').val('');
            $('#btn-formulario-adicionar-etiqueta').text("Adicionar Etiqueta");
            $('#status').prop('selectedIndex', 0);
            $('#descricao').val('');
            etiquetas = [];
            $("#lista-de-etiquetas").empty();

            mostrarFundo(false);
            mostrarDIV($('.container-adicionar-tarefa'), false);
            $(".campo-botao").css({ display: 'block' });
        } else {
            console.log("Erro ao atualizar:", resultado.mensagem || resultado);
            alert("Não foi possível atualizar a tarefa.");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        $('#btn-atualizar').prop('disabled', false);
        console.log("AJAX erro:", textStatus, errorThrown, jqXHR.responseText);
        alert("Erro na requisição ao atualizar a tarefa.");
    });
}


function concluirTarefa(tarefaID) {

    const idTarefa = tarefaID;

    $.ajax({
        url: '../api_core/cURL/cURL.php/?recurso=tarefa',
        type: 'POST',
        data: JSON.stringify({id: idTarefa, concluir: true }),
        contentType: 'application/json',
        dataType: 'json'
    })
        .done(function (resultado) {
            if (resultado.resposta == 200) {
                console.log("Tarefa concluída");
            } else {
                console.log("Falha ao concluir tarefa");
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Erro AJAX: ", textStatus, errorThrown, jqXHR);
        });

}

function removerTarefa(tarefaID) {


    if (!confirm("Tem certeza que deseja apagar a tarefa?")) {
        return;
    };

    $.ajax({
        type: 'DELETE',
        url: `../api_core/cURL/cURL.php/?recurso=tarefa&id=${tarefaID}`,
        dataType: 'json',
    }).done(function (resultado) {
        console.log(resultado);
        if (resultado.status == 200) {
            removerTarefaDaLista(tarefaID);
            $('#pesquisa').val('');
                console.log(tarefaID);
            console.log(resultado);
        } else {
            console.log(resultado.mensagem);
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
        const tarefas = resultado.dados;
        const pendentes = tarefas.filter(t => t.status === "pendente" || t.status === "em_andamento");
        const concluidas = tarefas.filter(t => t.status === "concluida");
           
        function renderizar(lista){
            for (let i = 0; i < lista.length; i++) {
                    const tarefa = lista[i];

                    const elemento = $(`
                    <div class="tarefa" id="tarefa_${tarefa.id}" style="display:none;">
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














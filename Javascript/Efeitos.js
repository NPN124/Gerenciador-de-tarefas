function mostrarFundo(mostrar = true, zIndex = 1) {
    const fundo = $('.fundo');
    fundo.css({ zIndex: zIndex });

    if (!mostrar) {
        fundo.animate({ opacity: 0.5 }, 100)
            .animate({ opacity: 0 }, 100, function () {
                fundo.css({ display: "none" });
            });
    } else {
        fundo.css({ display: "block" })
            .animate({ opacity: 0.5 }, 25)
            .animate({ opacity: 1 }, 25);
    }
}

function mostrarDIV(container, mostrar = true) {
    container.css({ zIndex: 2 });
    if (mostrar === false) {
        container.css({ display: "flex" });
        container.animate({ top: "50%" }, 200);
        container.animate({ top: "130%" }, 200, function () {
            container.css({ display: "none" });
        });
    } else {
        container.css({ display: "flex" });
        container.css({ top: "130%" }, 200);
        container.animate({ top: "50%" }, 200);
    }
}

function mostrarDIV_2(container, mostrar = true) {
    container.css({ zIndex: 99 });
    if (mostrar === false) {
        container.css({ display: "flex" });
        container.animate({ top: "50%" }, 200);
        container.animate({ top: "130%" }, 200, function () {
            container.css({ display: "none" });
        });
    } else {
        container.css({ display: "flex" });
        container.css({ top: "130%" }, 200);
        container.animate({ top: "50%" }, 200);
    }
}

function removerTarefaDaLista(tarefaID){
    $(`#tarefa_${tarefaID}`).fadeOut(500);
}




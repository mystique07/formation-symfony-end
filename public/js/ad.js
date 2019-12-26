$('#ad-image').click(function () {
    //Je récupère le numéro des futurs champs que je vais créer
    // fix bug const index = $('#ad_images div.form-group').length to;
    const index = + $('#widgets-counter').val();

    //Je récupère le prototype des entrée
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);



    //Je gère le button supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButtons();


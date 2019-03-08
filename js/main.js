$(document).ready(function() {
    $('.datepicker').datepicker({
        language: 'fr',
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
        });    

    $("#message").fadeTo(4000, 500).slideUp(500, function(){
        $("#message").slideUp(500);
    });

    $('#modal_liste').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var position = $('.drake_listes').children().length+1;
        console.log(position);

        var modal = $(this);
        modal.find('#position').attr('value', position);
    });

    $('#modal_tache').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget); // Button that triggered the modal
        var idListe = a.data('idliste') // Extract info from data-* attributes
        var nomListe = a.data('nomliste') // Extract info from data-* attributes
        var position = ($('[data-idliste="'+idListe+'"]').closest('.card').find('li').length)+1;

        var modal = $(this);
        modal.find('.modal-title')
            .text('Ajouter une tâche dans la liste ' + nomListe)

        modal.find('#position').attr('value', position);
        modal.find('#id_liste').attr('value', idListe);
    });

    function post_en_url(url, parametres) {
        //Création dynamique du formulaire
        var form = $('<form>');
        form.attr('method', 'POST');
        form.attr('action', url);
        //Ajout des paramètres sous forme de champs cachés
        for(var cle in parametres) {
            if(parametres.hasOwnProperty(cle)) {
                var champCache = $('<input/>');
                champCache.attr('type', 'hidden');
                champCache.attr('name', cle);
                champCache.attr('value', parametres[cle]);
                form.append(champCache);
            }
        }
        //Ajout du formulaire à la page et soumission du formulaire
        $(document.body).append(form);
        form.submit();
    }    
})

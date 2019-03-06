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

    // fonction pour rendre visible et invisible
    $("label").click(function (elem) {
        var value;
        if ( $(this).hasClass( "active" ) )
        {
            value = $(this)
                .removeClass('btn-secondary')
                .addClass('btn-success')
                .find('input').attr('checked', 'checked').val();
                $(this).find('span').text('visible');
        } else {            
            value = $(this)
                .removeClass('btn-success')
                .addClass('btn-secondary')
                .find('input').removeAttr('checked','').val();
                $(this).find('span').text('non');
        }
        var params = [];
        params['id'] = value;
        params['titre'] = $(this).find('input').attr('data-titre');

        post_en_url('../ateliers/atelier_visible.php', params);
       
     });
     //fin visible/invisible

    $('.next').click(function(){ $('.carousel').carousel('next');return false; });
    $('.prev').click(function(){ $('.carousel').carousel('prev');return false; });

    $('.carousel').carousel();

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

// Quand l'utilisateur fait descendre la page de 20px du haut de la page, affiche le bouton
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// Quand l'utilisateur clique sur le bouton, renvoie en haut de la page
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// apparait une alert pour le rappel
function rappel() 
{
    alert("Vous pourrez bientôt réservez vos cours de cuisine après avoir finaliser votre inscription");
}

// une alert pour le rappel du login
function rappellogin() 
{
    alert("N'oubliez pas de vous inscrire si vous n'avez pas encore de compte ! sinon bonne visite sur notre site ");
}

// apparait une alert pour le rappel
function connexion_reservation() 
{
    return confirm('Veuillez-vous connecter pour réserver cette atelier ?');
}

<?php $racine = (($_SERVER['REQUEST_URI'] == '/') || ($_SERVER['REQUEST_URI'] == '\/marmiteclassroom\/')) ? substr($_SERVER['REQUEST_URI'], 1):''; ?>
<?php $racine = (($_SERVER['REQUEST_URI'] == '/index.php'))?'': $racine; ?>
<?php require_once($racine. 'templates/header.php'); ?>
<div class="col-12">
    <h1>Ultimatetodolist, quand ta liste de tache prend de la couleur !</h1>
    <p>Page d'acceuil avec affichage des listes des tâche</p>     
</div>
<?php require_once($racine. 'templates/footer.php'); ?>

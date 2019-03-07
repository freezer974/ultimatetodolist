<?php


    $menu = 'roles';

    require_once('../templates/header.php');
    connexion_role('Admin');

?>
    <div class="col-md-4 mx-auto">

        <h1>Ajouter un role</h1>

        <form method="post" action="action_role.php">
            <input type="hidden" name="action" value="ajouter">
            <div class="form-group">
                <label for="label">Nom du role</label>
                <input type="text" class="form-control" id="label" name="label" placeholder="ex : Utilisateur" value="">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">retour</a>
        </form>
    </div>

<?php
    require_once('../templates/footer.php');
?>
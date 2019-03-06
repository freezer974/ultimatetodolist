<?php


    $menu = 'roles';

    require_once('../header.php');
    connexion_role('Admin');

?>
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

<?php
    require_once('../footer.php');
?>
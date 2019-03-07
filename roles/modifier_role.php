<?php
    $menu = 'roles';

    require_once('../templates/header.php');
    connexion_role('Admin');

    if (!empty($_GET['id']) && intval($_GET['id']) > 0):
        $id = intval($_GET['id']);
    else:
        redirection_page();
    endif;

    require_once('../functions/connexion_bdd.php');
    $requete = $bdd->prepare('SELECT * FROM roles WHERE id=:id' );
    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->execute();
    if (!($donnees = $requete->fetch())):
        redirection_page();
    endif;

?>
    <div class="col-md-4 mx-auto">

        <h1>Modifier un rôle</h1>

        <form method="post" action="action_role.php">
            <input type="hidden" name="action" value="modifier">
            <input type="hidden" name="id" value="<?= $donnees['id']; ?>">
            <div class="form-group">
                <label for="label">Nom du rôle</label>
                <input type="text" class="form-control" id="label" name="label" placeholder="ex : Utilisateur" value="<?= $donnees['label']; ?>">
            </div>
            <button type="submit" class="btn btn-warning">Modifier</button>
            <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">retour</a>
        </form>
    </div>

<?php
    require_once('../templates/footer.php');
?>
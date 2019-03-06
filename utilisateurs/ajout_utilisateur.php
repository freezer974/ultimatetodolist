<?php
    $menu = 'inscription';

    require_once('../templates/header.php');

    require_once('../functions/connexion_bdd.php');
    $requete = $bdd->prepare('SELECT * FROM roles WHERE label != :label ORDER BY label' );
    $requete->bindValue(':label', 'Admin', PDO::PARAM_STR);
    $requete->execute();
    $roles = $requete->fetchAll();
?>
    <div class="col-md-4 mx-auto">

        <h1>Inscription <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info  mb-2">retour</a></h1>
        <?php if (count($roles)): ?>
            <form method="post" action="action_utilisateur.php">
                <input type="hidden" name="action" value="ajouter">
                <div class="form-group">
                    <label for="nom">Nom de l'utilisateur</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="ex : Brad" value="" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom de l'utilisateur</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="ex : Pitt" value="" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="ex : example@gmail.com" value="" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe<span class="text-muted"> min. 8 caractères</span></label>
                    <input type="pasword" class="form-control" id="password" name="password" min="8" value="" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmation mot de passe<span class="text-muted"> min. 8 caractères</span></label>
                    <input type="pasword" class="form-control" id="confirm_password" name="confirm_password" min="8" value="" required>
                </div>
                <div class="form-group">
                    <label for="societe">Société</label>
                    <input type="text" class="form-control" id="societe" name="societe" placeholder="ex : SARL PimPom" value="" >
                </div>
                <div class="form-group">
                    <label for="id_role">Selectionner le rôle</label>
                    <select id="id_role" name="id_role" class="form-control" required>
                        <?php foreach($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"> <?= htmlentities($role['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        <?php else: ?>
            <p>Il n'y a pas de rôle. </p>
                <a class="btn btn-primary mb-2" href="/roles/ajout_role.php">Ajouter un rôle</a>
        <?php endif; ?>
        </div>

<?php
    require_once('../templates/footer.php');
?>
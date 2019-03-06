<?php

    if(empty($_SESSION['id'])):
        require_once('../functions.php');
        redirection_page();
    endif;

    if (!empty($_GET['id']) && intval($_GET['id']) > 0){
        $id = intval($_GET['id']);
    } else {
        redirection_page();
    }

    $menu = 'utilisateurs';

    require_once('../header.php');
    //connexion_role('Admin');

    if ($_SESSION['role'] != 'Admin'):
        $id = $_SESSION['id'];
    endif;

    require_once('../connexion_bdd.php');
    $requete = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = :id' );
    $requete->bindValue(':id', $id, PDO::PARAM_INT);
    $requete->execute();

    if (!($utilisateurs = $requete->fetch())):
        redirection_page();
    endif;
?>

            <h1>Modifier <?= intval($utilisateurs['id']); ?></h1>
            <!-- ajouter les liens pour modifier l'email et le mot de passe de manière sécuriser -->
            <!-- page pour valider l'envoi de mail / page pour vérification des mots de passe modifiés -->

            <form method="post" action="action_utilisateur.php">
                <input type="hidden" name="action" value="modifier">
                <input type="hidden" name="id" value="<?= intval($utilisateurs['id']); ?>">
                <div class="form-group">
                    <label for="nom">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="ex : The bing bang théorie" value="<?= htmlentities($utilisateurs['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom de l'utilisateur</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="ex : Pitt" value="<?= htmlentities($utilisateurs['prenom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" readonly id="email" name="email" placeholder="ex : example@gmail.com" value="<?= htmlentities($utilisateurs['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="societe">Société</label>
                    <input type="text" class="form-control" id="societe" name="societe" placeholder="ex : SARL PimPom" value="<?= htmlentities($utilisateurs['societe']); ?>" >
                </div>
                <?php

                $requete = $bdd->prepare('SELECT id_role FROM roles_utilisateurs WHERE id_utilisateur = :id');
                $requete->bindValue(':id', $utilisateurs["id"], PDO::PARAM_INT);
                $requete->execute();
                $liste = $requete->fetchAll(PDO::FETCH_COLUMN, 0);

                $requete = $bdd->prepare('SELECT * FROM roles ORDER BY id' );
                $requete->execute();
                if ($roles = $requete->fetch()): ?>
                    <div class="form-group">
                        <label for="role">Selectionner les roles</label>
                        <select id="role" name="role" class="form-control" required>
                            <?php do { ?>
                            <option value="<?= $roles['id'] ?>" <?= (in_array($roles['id'], $liste))? 'selected' : ''; ?>> <?= htmlentities($roles['label']) ?></option>
                            <?php   } while ($roles = $requete->fetch()); ?>
                        </select>
                    </div>
                <?php else: ?>
                    <p>Il n'y a pas de role. 
                        <a href="../roles/ajout_role.php" class="btn btn-primary mb-2">Ajouter un role</a>
                    </p>
                <?php endif; ?>
                <button type="submit" class="btn btn-warning">Modifier</button>
                <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">retour</a>
            </form>

<?php
    require_once('../footer.php');
?>
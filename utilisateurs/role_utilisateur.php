<?php

    $menu = 'utilisateurs';

    require_once('../header.php');
    connexion_role('Admin');

    if (!empty($_GET['id']) && intval($_GET['id']) > 0){
        $id = intval($_GET['id']);
    } else {
        flash('message', 'Aucun role trouvé', 'danger');
        redirection_page();
    }

    require_once('../connexion_bdd.php');

    $requete = $bdd->prepare('SELECT * FROM roles WHERE id = :id AND label != Admin');
    $requete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $requete->execute();
    $role = $requete->fetch();
    $requete->closeCursor();

    // S'il n'y a pas de résultat il affiche bien l'echo
    if (count($role) == 0)
    {
        flash('message', 'Aucun role trouvé', 'danger');
        redirection_page();
    }

    $requete = $bdd->prepare('SELECT u.id, u.nom, u.prenom, u.email, u.societe FROM utilisateurs u INNER JOIN roles_utilisateurs ru ON :id = ru.id_role AND u.id = ru.id_utilisateur ORDER BY nom'.$limit );
    $requete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $requete->execute();
    $utilisateurs = $requete->fetchAll();
    $totalPage = countPagePourTable(count($utilisateurs), $nbElementParPage);

    $requete->closeCursor();
?>
            <h1>Les utilisateurs avec le rôle <strong><?= $role['label']; ?></strong></h1>
            <a href="ajout_role.php" class="btn btn-primary mb-2">Ajouter un role</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Société</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                if (count($utilisateurs) != 0):
                foreach($utilisateurs as $utilisateur ):
                ?>
                    <tr>
                        <th scope="row"><?= htmlentities($utilisateur['id']); ?></th>
                        <td><?= htmlentities($utilisateur['nom']); ?></td>
                        <td><?= htmlentities($utilisateur['prenom']); ?></td>
                        <td><?= htmlentities($utilisateur['email']); ?></td>
                        <td><?= (!empty($utilisateur['societe'])? htmlentities($utilisateur['societe']): 'inconnu'); ?></td>
                        <td>
                            <a class="btn btn-info" href="voir_utilisateur.php?id=<?= intval($utilisateur['id']); ?>">Voir</a>
                            <a class="btn btn-warning" href="modifier_utilisateur.php?id=<?= intval($utilisateur['id']); ?>">Modifier</a>
                            <form class="form-inline mt-2" method='POST' action='action_utilisateur.php'>
                                <input type="hidden" name="id" value="<?= intval($utilisateur['id']); ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('&Ecirc;tes-vous sûr de vouloir supprimer l\'utilisateur?')" >Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php
                    endforeach;
                    else:
                ?>
                    <tr>
                        <td colspan="6">Il n'y a pas d'utilisateur enregistré pour le moment</td>
                    </tr>
                <?php
                    endif;
                ?>
                </tbody>
            </table>
 <?php
    require_once('../footer.php');
?>

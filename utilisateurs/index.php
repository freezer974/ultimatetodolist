<?php
    $menu = 'utilisateurs';

    require_once('../header.php');
    connexion_role('Admin');
    require_once('../connexion_bdd.php');

    // récupère tous les roles en tableau dans la variable $roles
    $requete = $bdd->prepare('SELECT * FROM roles');
    $requete->execute();
    $roles = $requete->fetchAll();
    $requete->closeCursor();

    // récupère tous les roles en tableau dans la variable $utilisateurs par rapport à la limite par page
    $requete = $bdd->prepare('SELECT id, nom, prenom, email, societe FROM utilisateurs ORDER BY nom'.$limit );
    $requete->execute();
    $utilisateurs = $requete->fetchAll();
    $requete->closeCursor();

    $totalPage = countPagePourTable(count($utilisateurs), $nbElementParPage);
?>
    <h1>Les utilisateurs</h1>
        <?php if (count($roles)): ?>

            <a href="ajout_utilisateur.php" class="btn btn-primary mb-2">Ajouter un utilisateur</a>
            <?php if (count($utilisateurs)): ?>
                <?php foreach($roles as $role): ?>
                    <a href="role_utilisateur.php?id=<?= $role['id'];?>" class="btn btn-info mb-2">Voir les <?= $role['label'];?>s</a>
                <?php endforeach; ?>
            <?php endif; ?>
            
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

                    if ( count($utilisateurs) != 0):
                        foreach($utilisateurs as $key => $utilisateur):
                ?>
                    <tr>
                        <th scope="row"><?= htmlentities($utilisateur['id']); ?></th>
                        <td><?= htmlentities($utilisateur['nom']); ?></td>
                        <td><?= htmlentities($utilisateur['prenom']); ?></td>
                        <td><?= htmlentities($utilisateur['email']); ?></td>
                        <td><?= (!empty($utilisateur['societe'])? htmlentities($utilisateur['societe']): 'inconnu'); ?></td>
                        <td>
                        <div class="container">
                            <div class="row">
                                <div class="mr-2">
                                    <a class="btn btn-info" href="voir_utilisateur.php?id=<?= intval($utilisateur['id']); ?>">Voir</a>
                                </div>
                                <div class="mr-2">
                                    <a class="btn btn-warning" href="modifier_utilisateur.php?id=<?= intval($utilisateur['id']); ?>">Modifier</a>
                                </div>
                                <div class="mr-2">
                                    <form class="form" method='POST' action='action_utilisateur.php'>
                                        <input type="hidden" name="id" value="<?= intval($utilisateur['id']); ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('&Ecirc;tes-vous sûr de vouloir supprimer l\'utilisateur?')" >Supprimer</button>
                                    </form>                                
                                </div>
                            </div>
                        </div>
                            
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
        <?php else: ?>
        <p>Il n'y a pas de rôle. </p>
            <a class="btn btn-primary mb-2" href="/roles/ajout_role.php">Ajouter un rôle</a>
    <?php endif; ?>
 <?php
    require_once('../footer.php');
?>

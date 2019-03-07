<?php
    $menu = 'roles';

    require_once('../templates/header.php');
    connexion_role('admin');
?>
    <div class="col-md-6 mx-auto">

            <h1>Les rôles</h1>
            <a href="../roles/ajout_role.php" class="btn btn-primary mb-2">Ajouter un rôle</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Label</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    require_once('../functions/connexion_bdd.php');
                    $requete = $bdd->prepare('SELECT * FROM roles ORDER BY id'.$limit );
                    $requete->execute();
                    $roles = $requete->fetchAll();
                    $requete->closeCursor();

                    $totalPage = countPagePourTable(count($roles), $nbElementParPage);

                    if (count($roles) != 0):
                        foreach($roles as $role):
                ?>
                    <tr>
                        <th scope="row"><?= htmlentities($role['id']); ?></th>
                        <td><?= htmlentities($role['label']); ?></td>
                        <td>
                            <a href="modifier_role.php?id=<?= htmlentities($role['id']); ?>" class="btn btn-warning float-left mr-2">Modifier</a>
                            <form class="form" method='POST' action='action_role.php'>
                                <input type="hidden" name="id" value="<?= intval($role['id']); ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('&Ecirc;tes-vous sûr de vouloir supprimer le role ?')" >Supprimer</button>
                            </form>                        
                        </td>
                    </tr>
                <?php
                        endforeach;
                    else:
                ?>
                    <tr>
                        <td colspan="3">Il n'y a pas de rôle</td>
                    </tr>
                <?php
                    endif;
                ?>
                </tbody>
            </table>
            </div>
 <?php
    require_once('../templates/footer.php');
?>

<?php
    // appel à la session
    require_once('../session.php');
    connexion_role('Admin');
    //appel à la base de donnée
    require_once('../connexion_bdd.php');

    //echo '<pre>' . var_export($_POST, true) . '</pre>';
    //die();

    if (!empty($_POST['action'])):
        $action = htmlentities($_POST['action']);


        // test la présence et la validité des données
        if (!empty($_POST['label'])):            
            if ($action == 'ajouter'):
                $req = $bdd->prepare('INSERT INTO roles (label) VALUES (:label)');
                $req->bindValue(':label', ChaineAvecMajuscule($_POST['label']), PDO::PARAM_STR);
                $req->execute();
                $req->closeCursor();

                flash('message', 'Le rôle <strong>'. htmlentities(ChaineAvecMajuscule($_POST['label'])) .'</strong> a été ajouté');
                redirection_page();
            endif;

            if (($action == 'modifier') && !empty($_POST['id']) && intval($_POST['id']) > 0):

                $req = $bdd->prepare('UPDATE roles SET label=:label WHERE id=:id');
                $req->bindValue(':id', ChaineAvecMajuscule($_POST['id']), PDO::PARAM_INT);
                $req->bindValue(':label', $_POST['label'], PDO::PARAM_STR);
                $req->execute();
                $req->closeCursor();

                flash('message', 'Le rôle a été modifié');
                redirection_page();
            endif;
        endif; 

        if (($action == 'supprimer') && !empty($_POST['id']) && intval($_POST['id']) > 0):

            delete($bdd, 'roles' , 'id', $_POST['id']);

            flash('message', 'Le rôle ' . $label . ' a été supprimé');
            redirection_page();
        endif; 
    endif;

    flash('message', 'Les données ne sont pas parvenues', 'danger');
    redirection_page();
?>
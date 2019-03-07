<?php
    // appel à la session
    require_once('../functions/session.php');
    //appel à la base de donnée
    require_once('../functions/connexion_bdd.php');

    //echo '<pre>' . var_export($_POST, true) . '</pre>';
    //die();
    
    if (!empty($_POST['action'])):

        $action = htmlentities($_POST['action']);
        
        // test la présence
        if (!empty($_POST['nom']) && !empty($_POST['description']) && !empty($_POST['position'])):

            // si on a l'action ajouter
            if ($action == 'ajouter') :
                $req = $bdd->prepare('INSERT INTO listes (nom,description,position) VALUES (:nom,:description,:position)');
                $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':description', ChaineAvecMajuscule($_POST['description']), PDO::PARAM_STR);
                $req->bindValue(':position', $_POST['position'], PDO::PARAM_int);
                $req->execute();
                $id_tache = $bdd->lastInsertId();
                $req->closeCursor();

                $req = $bdd->prepare('INSERT INTO  listes_utilisateurs(id_liste,id_utilisateur) VALUES (:id_liste,:id_utilisateur)');
                $req->bindValue(':id_utilisateur', $_SESSION['id'], PDO::PARAM_INT);
                $req->bindValue(':id_liste', $id_tache, PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

    

                flash('message', 'La tâche <strong>'. htmlentities($_POST['nom']).'</strong> a été ajouté à la liste '. htmlentities($_POST['nom_liste']));
                redirection_page();
            endif;

            // si on a l'action modifier
            if (($action == 'modifier') && !empty($_POST['id']) && intval($_POST['id']) > 0) :

                //préparation de la requête, mise au norme des variable et envoye
                $req = $bdd->prepare('UPDATE listes SET nom = :nom, description = :description, WHERE id = :id');
                $req->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':description', ChaineAvecMajuscule($_POST['description']), PDO::PARAM_STR);
                $req->bindValue(':position', $_POST['position'], PDO::PARAM_STR);
                $req->execute();
                $req->closeCursor();

                $req->execute();
                $req->closeCursor();

                flash('message', 'Les données de la tâche ont bien été modifiées');
                redirection_page();
            endif;

        endif; 

        // si on a l'action supprimer
        if (($action == 'supprimer') && !empty($_POST['id']) && intval($_POST['id']) > 0):

            delete($bdd, 'listes', 'id', $_POST['id']);

            flash('message', 'La tâche a été supprimé');
            redirection_page();
        endif; 
    endif;

    flash('message', 'Les données ne sont pas parvenues', 'danger');
    redirection_page();
    
?>
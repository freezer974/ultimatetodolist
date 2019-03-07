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
        if (!empty($_POST['action']) && !empty($_POST['nom']) && !empty($_POST['description']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])  && !empty($_POST['position'])):
            
            // si on a l'action ajouter
            if ($action == 'ajouter') :
                $req = $bdd->prepare('INSERT INTO taches (nom,description,start_date,end_date,position) VALUES (:nom,:description,:start_date,:end_date, :position)');
                $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':description', ChaineAvecMajuscule($_POST['description']), PDO::PARAM_STR);
                $req->bindValue(':start_date', str_replace('T', " ", $_POST['start_date']).":00", PDO::PARAM_STR);
                $req->bindValue(':end_date', str_replace('T', " ", $_POST['end_date']).":00", PDO::PARAM_STR);
                $req->bindValue(':position', $_POST['position'], PDO::PARAM_INT);
                $req->execute();
                $id_tache = $bdd->lastInsertId();
                $req->closeCursor();

                $req = $bdd->prepare('INSERT INTO taches_utilisateurs(id_tache,id_utilisateur) VALUES (:id_tache,:id_utilisateur)');
                $req->bindValue(':id_utilisateur', $_SESSION['id'], PDO::PARAM_INT);
                $req->bindValue(':id_tache', $id_tache, PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                $req = $bdd->prepare('INSERT INTO listes_taches(id_liste, id_tache) VALUES (:id_liste,:id_tache)');
                $req->bindValue(':id_liste', $_POST['id_liste'], PDO::PARAM_INT);
                $req->bindValue(':id_tache', $id_tache, PDO::PARAM_INT);
                $req->execute();
                $req->closeCursor();

                flash('message', 'La tâche <strong>'. htmlentities($_POST['nom']).'</strong> a été ajouté à la liste '. htmlentities($_POST['nom_liste']));
                redirection_page();
            endif;

            // si on a l'action modifier
            if (($action == 'modifier') && !empty($_POST['id']) && intval($_POST['id']) > 0) :

                //préparation de la requête, mise au norme des variable et envoye
                $req = $bdd->prepare('UPDATE taches SET nom = :nom, description = :description, date_start = STR_TO_DATE(:start_date,\'%d/%m/%Y\'), end_date = STR_TO_DATE(:end_date,\'%d/%m/%Y\') WHERE id = :id');
                $req->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':description', ChaineAvecMajuscule($_POST['description']), PDO::PARAM_STR);
                $req->bindValue(':date_start', $_POST['date_start'], PDO::PARAM_STR);
                $req->bindValue(':end_date', $_POST['end_date'], PDO::PARAM_STR);
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

            delete($bdd, 'taches', 'id', $_POST['id']);

            flash('message', 'La tâche a été supprimé');
            redirection_page();
        endif; 
    endif;

    flash('message', 'Les données ne sont pas parvenues', 'danger');
    redirection_page();
    
?>
<?php
    // appel à la session
    require_once('../session.php');
    //appel à la base de donnée
    require_once('../connexion_bdd.php');

    //echo '<pre>' . var_export($_POST, true) . '</pre>';
    //die();
    
    if (!empty($_POST['action'])):

        $action = htmlentities($_POST['action']);
        
        // test la présence
        if (!empty($_POST['action']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['password'])):
            $societe = (!empty($_POST['societe']))?$_POST['societe']:null;

            // si on a l'action ajouter
            if ($action == 'ajouter') :

                if ($_POST['password'] == $_POST['confirm_password']):

                    $req = $bdd->prepare('SELECT email FROM utilisateurs FROM email = :email');
                    $req->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                    $req->execute();
                    $utilisateur = $req->fetch();
                    $req->closeCursor();

                    if ($utilisateurs):

                        $req = $bdd->prepare('INSERT INTO utilisateurs (nom,prenom,email,password,societe) VALUES (:nom,:prenom,:email,:password,:societe)');
                        $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                        $req->bindValue(':prenom', ChaineAvecMajuscule($_POST['prenom']), PDO::PARAM_STR);
                        $req->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                        $req->bindValue(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
                        $req->bindValue(':societe', $societe, PDO::PARAM_STR);
                        $req->execute();
                        $id_utilisateur = $bdd->lastInsertId();
                        $req->closeCursor();

                        $req = $bdd->prepare('INSERT INTO roles_utilisateurs(id_role,id_utilisateur) VALUES (:id_role,:id_utilisateur)');
                        $req->bindValue(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
                        $req->bindValue(':id_role', $_POST['id_role'], PDO::PARAM_INT);
                        $req->execute();
                        $req->closeCursor();

                        flash('message', '<strong>'. htmlentities($_POST['nom']) . ' ' . htmlentities($_POST['prenom']) .'</strong> vous êtes ajouter');
                        redirection_page();
                    else:
                        flash('message', 'Un utilisateur avec le même email existe dans notre base.', 'danger');
                        redirection_page();
                    endif; 
                else:
                    flash('message', 'Le mot de passe sont pas identique', 'danger');
                    redirection_page(); 
                endif;
            endif;

            // si on a l'action modifier
            if (($action == 'modifier') && !empty($_POST['id']) && intval($_POST['id']) > 0) :

                //préparation de la requête, mise au norme des variable et envoye
                $req = $bdd->prepare('UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email, societe = :societe  WHERE id = :id');
                $req->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                $req->bindValue(':nom', ChaineAvecMajuscule($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':prenom', ChaineAvecMajuscule($_POST['prenom']), PDO::PARAM_STR);
                $req->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $req->bindValue(':societe', $societe, PDO::PARAM_STR);
                $req->execute();
                $req->closeCursor();

                // suppression des variables quine sont plus liées dans la table de liaison
                delete($bdd, 'roles_utilisateurs', 'id_utilisateur', $_POST['id']);

                // ajouter des variables dans la table de liaison
                $req = $bdd->prepare('INSERT IGNORE INTO roles_utilisateurs(id_role, id_utilisateur) VALUES (:id_role, :id_utilisateur)');
                $req->bindValue(':id', $_POST['id_role'], PDO::PARAM_INT);
                $req->bindValue(':id_utilisateur', $_POST['id'], PDO::PARAM_INT);

                $req->execute();
                $req->closeCursor();

                flash('message', 'Les données ont bien été modifiées');
                redirection_page();
            endif;

        endif; 

        if ($action == 'connexion'):

            //  Récupération des informtions l'utilisateur et de son role
            $req = $bdd->prepare('SELECT u.id, CONCAT(u.nom, \' \', u.prenom) AS nom, u.societe, ru.id_role, r.label, password 
                                    FROM utilisateurs u
                                    INNER JOIN roles_utilisateurs ru 
                                        ON u.id = ru.id_utilisateur
                                        INNER JOIN roles r
                                        ON r.id = ru.id_role
                                    WHERE (email = :email)');
            $req->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $req->execute();

            $resultat = $req->fetch();

            // Comparaison du pass envoyé via le formulaire avec la base
            $isPasswordCorrect = password_verify($_POST['password'], $resultat['password']);           

            if (!$resultat):
                flash('message', 'Mauvais identifiant ou mot de passe !', 'danger');
                redirection_page();
            else:
                if ($isPasswordCorrect):
                    $_SESSION['id'] = $resultat['id'];
                    $_SESSION['nom'] = $resultat['nom'];
                    $_SESSION['societe'] = $resultat['societe'];
                    $_SESSION['role'] = $resultat['label'];
                    $_SESSION['email'] = htmlentities($_POST['email']);
                    
                    if (!empty($_POST['remember']) && (htmlentities($_POST['remember']) == 'on')):
                        setcookie('email', $_SESSION['email'], time() + 365*24*3600, null, null, false, true);
                        setcookie('password', $resultat['password'], time() + 365*24*3600, null, null, false, true);
                    endif;

                    flash('message', 'Vous êtes connecté en tant que ' . $resultat['nom'] . ' ' . $resultat['prenom']);
                    redirection_page(); 

                else:
                    flash('message', 'Mauvais identifiant ou mot de passe !', 'danger');
                    redirection_page();
                endif;
            endif;
        endif;

        // si on a l'action deconnexion
        if ($action == 'deconnexion') : 

            flash('message', $_SESSION['nom'] . 'vous êtes déconnecté, quelle dommage ! A bientôt.');   
            
            // Suppression des variables de session et de la session
            $_SESSION = array();
            session_destroy();

            // Suppression des cookies de connexion automatique
            if (!empty($_COOKIE['email']) && !empty($_COOKIE['password'])):
                setcookie('email', '');
                setcookie('password', '');
            endif;

            redirection_page();
        endif;

        // si on a l'action supprimer
        if (($action == 'supprimer') && !empty($_POST['id']) && intval($_POST['id']) > 0):

            delete($bdd, 'utilisateurs', 'id', $_POST['id']);

            flash('message', 'L\'utilisateur a été supprimé');
            redirection_page();
        endif; 
    endif;

    flash('message', 'Les données ne sont pas parvenues', 'danger');
    redirection_page();
    
?>
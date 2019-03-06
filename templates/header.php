<?php
    //echo '<pre>'.var_export($_SERVER).'</pre>';

    if ($_SERVER['REQUEST_URI'] == "/ultimatetodolist/" || $_SERVER['REQUEST_URI'] == "/"):
        $adresse = '';
    else:
        $adresse = '../';
    endif;
    //S'assurez que la session existe
    require_once($adresse . 'functions/session.php');

    if (isset($_GET['page'])) {
        $page = intval($_GET['page']);
    } else {
        $page = 1;
    }

    $nbElementParPage = 5;
    $offset = ($page-1) * $nbElementParPage;
    $totalPage = 0;
    $limit = ' LIMIT '.$offset.','.$nbElementParPage;

    if (empty($menu)){
        $menu = 'accueil';
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>UltimateToDoList</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">    <link rel="stylesheet" type="text/css" media="screen" href="../css/main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css">
        <link rel="stylesheet" href="../css/animate.css">
        <link rel="stylesheet" href="../css/todolist.css">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg  <?= ($_SESSION['role'] == 'Admin')? 'navbar-light bg-warning':'navbar-dark bg-dark bg-dark'; ?> ">
        <a class="navbar-brand" href="#"><img class="logo-nav" src="<?= $url_image ?>logo.png" alt="" /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item <?= (($menu == 'accueil')? 'active' : ''); ?>" >
                    <a class="nav-link" href="<?= $adresse; ?>">Accueil <?= (($menu == 'accueil')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
            <?php if ($_SESSION['role'] == 'Admin'): ?>
                <li class="nav-item <?= (($menu == 'roles')? 'active' : ''); ?> " >
                    <a class="nav-link" href="<?= $adresse; ?>roles">Rôles <?= (($menu == 'roles')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
                <li class="nav-item <?= (($menu == 'utilisateurs')? 'active' : ''); ?>" >
                    <a class="nav-link" href="<?= $adresse; ?>utilisateurs">Utilisateurs <?= (($menu == 'utilisateurs')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
            <?php endif;?>
            <?php if ($_SESSION['role'] == 'Membre'): ?>
                <li class="nav-item <?= (($menu == 'todolist')? 'active' : ''); ?>" >
                     <a class="nav-link" href="<?= $adresse; ?>todolists">Todolist <?= (($menu == 'todolist')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
            <?php endif; ?>
            </ul>

        <?php if (!empty($_SESSION['role'])): ?>
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item <?= (($menu == 'profil')? 'active' : ''); ?>">
                    <a class="nav-link" href="#"><?= $_SESSION['nom']; ?> <span class="font-italic small">(<?= $_SESSION['role']; ?>)</span></a>
                </li>
                <li class="nav-item <?= (($menu == 'deconnexion')? 'active' : ''); ?>">
                    <form class="form-inline my-2 my-lg-0" action="<?= $adresse; ?>utilisateurs/action_utilisateur.php" method="POST">
                        <input type="hidden" value='deconnexion' name='action'>
                        <button type="submit" class="btn btn-secondary">Deconnexion</button>
                    </form>
                </li>
            </ul>
        <?php else: ?>
            <ul class="navbar-nav ml-md-auto">
                <li class="nav-item <?= (($menu == 'login')? 'active' : ''); ?>">
                    <a class="nav-link" href="<?= $adresse; ?>utilisateurs/connexion_utilisateur.php">Login <?= (($menu == 'login')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
                <li class="nav-item <?= (($menu == 'inscription')? 'active' : ''); ?>">
                    <a class="nav-link" href="<?= $adresse; ?>utilisateurs/ajout_utilisateur.php">Inscription <?= (($menu == 'inscription')? '<span class="sr-only">(current)</span>' : ''); ?></a>
                </li>
            </ul>
        <?php endif; ?>
        </div>
    </nav>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <?php flash( 'message' ); ?>
            </div>
            <button id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
            


<?php $menu = 'todolist';
    require_once('../templates/header.php');
    
    if(empty($_SESSION['id'])):
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

<div class="col-12 todolist">
    <!-- Section Todolist (modif de la suppression par un système d'archivage) -->
    <main name="main" class="flex-shrink-0">
        <section>
            <div class="container-fluid">
                <div class="row px-4 p-1">
                    <div class="container-fluid">
                        <div class="row d-flex align-items-center">
                            <p class="h5 font-weight-bold">To do list</p>
                            <span class="ajouteList mx-2 text-primary h5">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="container-fluid scrollH">
                <div class="row flex-row flex-nowrap card-deck drake_listes">
                    <div class="ml-4 mb-4 scrollV">
                        <div class="card card-block m-0">
                            <div class="card-body p-2">
                                <p class="card-title justify-content-between d-flex align-items-center font-weight-bold h6 mb-1">List 0</p>
                                <span class="removeList mr-2 mt-2"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                                <p class="card-text text-muted mb-2">Manage your tasks easily.</p>
                            </div>            
                            <ul class="list-group list-group-flush drake_taches">
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Walk the dog this evening
                                    </div>
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-check-square-o mr-2 text-success" aria-hidden="true"></i></span>
                                        Go shopping at 3 PM
                                    </div>
                                    <span class="archive text-danger"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Sleep well tonight
                                    </div>                    
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Keep coding 'till you're dead
                                    </div>
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Enjoy every moment you have
                                    </div>                 
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                            </ul>
                            <div class="card-footer p-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Votre tâche ici ...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary button-addon" type="button" id="button-addon">Ajouter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 mb-4 scrollV d-none">
                        <div class="card card-block m-0">
                            <div class="card-body p-2">
                                <p class="card-title justify-content-between d-flex align-items-center font-weight-bold h6 mb-1">List </p>
                                <span class="removeList mr-2 mt-2"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                                <p class="card-text text-muted mb-2">Manage your tasks easily.</p>
                            </div>            
                            <ul class="list-group list-group-flush drake_taches">
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Walk the dog this evening
                                    </div>
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-check-square-o mr-2 text-success" aria-hidden="true"></i></span>
                                        Go shopping at 3 PM
                                    </div>
                                    <span class="archive text-danger"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Sleep well tonight
                                    </div>                    
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Keep coding 'till you're dead
                                    </div>
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <div class="check w-100">
                                        <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                        Enjoy every moment you have
                                    </div>                 
                                    <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                </li>
                            </ul>
                            <div class="card-footer p-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Votre tâche ici ...">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary button-addon" type="button" id="button-addon">Ajouter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>    
</div>
<?php require_once('../templates/footer.php'); ?>

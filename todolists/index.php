<?php $menu = 'todolist';
    require_once('../templates/header.php');
    
    if(empty($_SESSION['id'])):
        redirection_page();
    endif;

    $menu = 'todolist';

    require_once('../templates/header.php');
    //connexion_role('Admin');

    if ($_SESSION['role'] != 'Admin'):
        $id = $_SESSION['id'];
    endif;

    $position_liste = 1;
    $position_tache = 1;

    require_once('../functions/connexion_bdd.php');
    $requete = $bdd->prepare('SELECT l.id, l.nom, l.description, l.position FROM listes l INNER JOIN listes_utilisateurs lu ON lu.id_liste = l.id WHERE lu.id_utilisateur = :id ORDER BY position ASC' );
    $requete->bindValue(':id', $_SESSION["id"], PDO::PARAM_INT);
    $requete->execute();
    $listes = $requete->fetchAll();
    $requete->closeCursor();
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
                                <i class="fa fa-plus-circle" aria-hidden="true" data-toggle="modal" data-target ="#modal_liste"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="container-fluid scrollH">
                <div class="row flex-row flex-nowrap card-deck drake_listes">
                <?php foreach($listes as $liste): ?>
                    <div class="ml-4 mb-4 scrollV" data-position="<?= $position_liste++; ?>" data-id="<?= $liste['id']; ?>">
                        <div class="card card-block m-0">
                            <div class="card-body p-2">
                                <p class="card-title justify-content-between d-flex align-items-center font-weight-bold h6 mb-1"><?= $liste['nom']; ?></p>
                                <span class="removeList mr-2 mt-2"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i></span>
                                <p class="card-text text-muted mb-2 text-truncate"><?= $liste['description']; ?></p>
                            </div>            
                            <ul class="list-group list-group-flush drake_taches py-1 b-none" id="<?= $liste['id']; ?>">
                                
                                <?php $requete = $bdd->prepare('SELECT t.id, t.nom, t.description, t.start_date, t.end_date, t.position 
                                                                    FROM taches t 
                                                                    INNER JOIN listes_taches tl 
                                                                        ON tl.id_tache = t.id 
                                                                    WHERE tl.id_liste = :id 
                                                                    ORDER BY position ASC' ); ?>
                                <?php $requete->bindValue(':id', $liste['id'], PDO::PARAM_INT); ?>
                                <?php $requete->execute(); ?>
                                <?php $taches = $requete->fetchAll(); ?>
                                <?php $requete->closeCursor(); ?>
                                
                                <?php foreach($taches as $tache): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-2" data-position="<?= $position_tache++; ?>" data-id="<?= $tache['id']; ?>" data-liste_id="<?= $liste['id']; ?>">
                                        <div class="check w-100">
                                            <span class="checkbox"><i class="fa fa-square-o text-muted" aria-hidden="true"></i></span>
                                            <?= $tache['nom']; ?>
                                        </div>
                                        <span class="archive text-danger d-none"><i class="fa fa-archive" aria-hidden="true"></i></span>
                                    </li>
                                <?php endforeach; ?>
                                
                            </ul>
                            <div class="card-footer text-center bg-white border-top-0">
                                <div class="d-inline p-1  border-success bg-success text-white rounded" name="action" data-toggle="modal" data-position="1" data-target="#modal_tache" data-idliste="<?= $liste['id']; ?>" data-nomliste="<?= $liste['nom']; ?>" id="action" value="ajouter">Ajouter une tâche</div> 
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
            <!-- The Modal ajout liste-->

<div class="modal fade" id="modal_liste" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!--Header modal -->
      <div class="modal-header">
        <h5 class="modal-title">Ajouter une liste</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div id="dialog-form-list" title="Ajouter une liste">
            <form id="formList" method="POST" action="../listes/action_liste.php">
                <label>Nom:</label>
                <input type="text" class="form-control" name="nom">
                <label>Description:</label>
                <input type="text" class="form-control" name="description">
                <input type="hidden" name="position" id="position" value="">
                <input type="submit" class="btn btn-primary" id="btn-list" value="ajouter" name="action">
            </form>
        </div>
      </div>
      <!--FIN modal-body-->
    </div>
  </div>
</div>
<!-- The Modal ajout tache -->

<div class="modal fade" id="modal_tache" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <!--Header modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter une tâche</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
        <div id="dialog-form" title="Create new order">
             <form method="POST" action="../taches/action_tache.php">
                  <!--Nom-->
                    <div class="form-group">
                      <label for="nom_tache">Nom tâche : </label>
                      <input class="form-control"  type="text" name="nom"  id="nom_tache" placeholder="ex:Tâche 1">

                    </div>
                    <!--Description-->
                    <div class="form-group">
                      <label >Description : </label>
                      <textarea rows="2" cols="50" class="form-control" name="description" placeholder="Ajout d'une description">

                      </textarea>
                    </div>
                    <!--Strat-date-->
                    <div class="form-group">
                      <label >Date début : </label>
                      <input type="datetime-local" name="start_date">
                    </div>
                    <!--End-Data-->
                    <div class="form-group">
                      <label >Date fin : </label>
                      <input type="datetime-local" name="end_date">
                    </div>
                    <!--Position-->
                    <input type="hidden" id="position" name="position" value="">
                    <!--id_liste-->
                    <input type="hidden" id="id_liste" name="id_liste" value="">

                    <!--Submit-->
                    <input type="submit" value="ajouter" name="action" class="btn btn-primary btn-block">

                  </form>
        </div>
    </div>
  </div>
</div>
        </section>
        
    </main>    
</div>
<?php require_once('../templates/footer.php'); ?>

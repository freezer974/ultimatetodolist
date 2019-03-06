<?php

    $menu = 'login';

    require_once('../templates/header.php');
?>
    <div class="col-md-4 mx-auto">
        <h1>Connexion</h1>
        <form method="post" action="action_utilisateur.php">
            <input type="hidden" name="action" value="connexion">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="ex : example@gmail.com" value="" required>
            </div>
            <div class="form-group">
                <label for="password">Password <span class="text-muted small">min. 8 caractères</span></label>
                <input type="pasword" class="form-control" id="password" name="password" min="8" value="" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
            <a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-info">retour</a>
        </form>
    </div>
    

<?php
    require_once('../templates/footer.php');
?>
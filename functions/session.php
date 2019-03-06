<?php

    if ($_SERVER['REQUEST_URI'] == "/ultimatetodolist/" || $_SERVER['REQUEST_URI'] == "/"):
        $adresse = '';
    else:
        $adresse = '../';
    endif;
    //echo '<pre>'.var_export($_SERVER).'</pre>';

   //Ensure that a session exists (just in case)
    if(session_status() === PHP_SESSION_NONE):
        session_start();
    endif;
    
    require_once($adresse.'functions/functions.php');
    if (empty($_SESSION['role'])):
        $_SESSION['role'] = '';
    endif;
    
?>
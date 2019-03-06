<?php
   //Ensure that a session exists (just in case)
   $racine = (($_SERVER['REQUEST_URI'] == '/') || ($_SERVER['REQUEST_URI'] == '\/marmiteclassroom\/')) ? substr($_SERVER['REQUEST_URI'], 1):'../';
   $racine = (($_SERVER['REQUEST_URI'] == '/index.php'))?'': $racine;

    if(session_status() === PHP_SESSION_NONE):
        session_start();
    endif;
    
    require_once($racine . 'functions/functions.php');

    if (empty($_SESSION['role'])):
        $_SESSION['role'] = '';
    endif;
    
?>
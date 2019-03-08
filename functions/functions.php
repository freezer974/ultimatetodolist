<?php

/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash( $name = '', $message = '', $class = 'success fadeout-message' )
{
    //We can only do something if the name isn't empty
    if( !empty( $name ) )
    {
        //No message, create it
        if( !empty( $message ) && empty( $_SESSION[$name]))
        {
            if( !empty( $_SESSION[$name] ) )
            {
                unset( $_SESSION[$name] );
            }
            if( !empty( $_SESSION[$name.'_class'] ) )
            {
                unset( $_SESSION[$name.'_class'] );
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        }
        //Message exists, display it
        elseif( !empty( $_SESSION[$name] ) && empty( $message ))
        {
            $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
            echo '<div id ="message" class="alert alert-'.$class.' alert-dismissible fade show" role="alert">'
                    .$_SESSION[$name]
                    .'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                 </div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

/**
 * Fonction qui permet de mettre un mot avec la première lettre en majuscule et le reste en minuscule
 * et converti tous les caractères spéciaux en entités HTML
 * @access public
 * @param string titre
 * @return string titre converti
 */
function ChaineAvecMajuscule($titre){

    return ucfirst(strtolower(htmlentities($titre)));
}

/**
 * Fonction qui permet de supprimer une entité d'une base de donnée
 * @access public
 * @param instance PDO CLass bdd
 * @param string table
 * @param string entity
 * @param string value
 * @return boolean true
 */
function delete($bdd, $table, $entity, $value){

    $delete = 'DELETE FROM ' . $table . ' WHERE ' . $entity . ' = ' . $value;
    $req = $bdd->query($delete);
    $req->closeCursor();

    return true;
}

/**
 * Fonction qui permet de compter le nombre de page pour pour la table
 * @access public
 * @param int count
 * @param int nbElementParPage
 * @return int retourne le plus petit entier supérieur ou égal au nombre donné
 */
function countPagePourTable($count, $nbElementParPage){
    return ceil($count / $nbElementParPage);
}

/**
 * Fonction qui permet de donner accès au page via le controle de role
 * @access public
 * @param string role
 * @param int nbElementParPage
 * @return mixed boolean | message flash 
 */
function connexion_role($role){
    if (is_array($role)):
        foreach($role AS $r):
            if (ChaineAvecMajuscule($r) == $_SESSION['role']):
                return true;
            endif;
        endforeach;
    endif;
    if (is_string($role)):
        if (ChaineAvecMajuscule($role) === $_SESSION['role']):
            return true;
        endif;
    endif;

    flash('message', 'Vous n\'êtes pas autorisé à faire ça', 'danger' );
    header('Location: /');
}

/**
 * Fonction qui permet de rediriger l'utilisateur
 * @access public
 * @param mixed string|null page
 * @return header redirection  
 */
function redirection_page($page = null){


    if (empty($page)):
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    else:
        $referer = $page;
    endif;
    header('Location: '  . $referer);
    exit();
}



/**
 * Fonction qui test la présence du token en session lors de l'envoi d'un formulaire
 * @access public
 * @param token clé créer lors de l'ouverture de la session de l'utilisateur transmis au formulaire
 * @return boolean true | false
 */
function test_token($token){
    if (!empty($token)):
        if (hash_equals($_SESSION['token'], $token)) :
            return true;  
        else:
            // Log this as a warning and keep an eye on these attempts
        endif;
    endif;
}


function format_date($date_at){
    $ddate = DateTime::createFromFormat('Y-m-d H:i:s', $date_at);
    return $ddate->format('d/m/Y');
}

function format_date_fr($date_at){
    $mois = ['','Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    $ddate = DateTime::createFromFormat('Y-m-d H:i:s', $date_at);
    $date_fr = $ddate->format('d') .' '. $mois[$ddate->format('n')]. ' ' . $ddate->format('Y');
    return $date_fr;
}

function format_heure($time_at){
    $dtime = DateTime::createFromFormat('H:i:s', $time_at);
    return $dtime->format('H:i');
}
function format_heure_h($time_at){
    $dtime = DateTime::createFromFormat('H:i:s', $time_at);
    $heure = $dtime->format('G:i');
    return str_replace(':', 'h', $heure);
}

function getExtension ($mime_type){

    $extensions = array('image/jpeg' => 'jpeg',
                        'image/png' => 'png',
                        'image/gif' => 'gif'
                        );

    // Add as many other Mime Types / File Extensions as you like
    return $extensions[$mime_type];

}

function upload_image($index, $filename, $maxsize){
    // on récupère l'objet AWS S3
    require('../vendor/autoload.php');

    // ca va lire les variables AWS_ACCESS_KEY_ID et AWS_SECRET_ACCESS_KEY enregistrer dans l'environnement de heroku 
    // on détermine le serveur vers lequel les fichiers seront traitées
    $s3 = new Aws\S3\S3Client([
        'version'  => '2006-03-01',
        'region'   => 'eu-west-3',
    ]);
    // Bucket, nom du compartiment créé chez AWS qui va contenir les images  
    $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');

    //Test1: fichier correctement uploadé et sans erreur
    if(isset($_FILES[$index]) && $_FILES[$index]['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES[$index]['tmp_name'])):

        //Test2: taille limite
        if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;

        //Test3: type de fichier
        if (!$ext = getExtension($_FILES[$index]['type'])) return FALSE;
       
        try {

            //Enregistrement chez AWS S3
            $upload = $s3->upload($bucket, $_FILES[$index]['name'], fopen($_FILES[$index]['tmp_name'], 'rb'), 'public-read');

            //renomme le fichier avec $filename //copie d'abord
            $result = $s3->copyObject(array(
                'ACL' => 'public-read',
                // on pointe le compartiment chez AWS
                'Bucket' => $bucket,
                // CopySource, fichier à copier
                'CopySource' =>  $bucket.'/'.$_FILES[$index]['name'],
                // Key, nouveau nom de fichier
                'Key' => $filename.'.'.$ext,
                // directive à réaliser, ici on remplace
                'MetadataDirective' => 'REPLACE'
                ));    

                // efface le fichier télécharger avec son nom d'origine
                delete_image($_FILES[$index]['name']);

                //retourne le nom du fichier à enregistrer dans la base de donnée
                return $filename.'.'.$ext;    

        } catch(Exception $e) { 
            return FALSE;
        } 
    endif;  
}

function delete_image($image){
    require('../vendor/autoload.php');

    // this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
    $s3 = new Aws\S3\S3Client([
        'version'  => '2006-03-01',
        'region'   => 'eu-west-3',
    ]);
    $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET_NAME" config var in found in env!');

    // Delete an object from the bucket.
    $s3->deleteObject([
        'Bucket' => $bucket,
        'Key'    => $image
    ]);
}

function existe_image($image){
    require('../vendor/autoload.php');

    // this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
    $s3 = new Aws\S3\S3Client([
        'version'  => '2006-03-01',
        'region'   => 'eu-west-3',
    ]);

    $bucket = getenv('S3_BUCKET_NAME');

    return $s3->doesObjectExist($bucket, $image);
}
?>
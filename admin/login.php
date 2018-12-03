<?php 
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

$error ='';

$tabPost = [
    'mail'=>'',
    'password'=>''
];


try
{
    

    if(array_key_exists('mail', $_POST))
    {
        foreach($tabPost as $champ => $value )
        {
            if( isset($_POST[$champ]) && trim($_POST[$champ]!= '') )
            {
                $tabPost[$champ] = $_POST[$champ];
            }
            else
            {
                $error ='<p>Formulaire mal renseigné</p>';
            }
        }
       


        // si le mail n'est pas valide, renvoir False, sinon renvoie la string
        if ( !filter_var($tabPost['mail'], FILTER_VALIDATE_EMAIL) )
        {
            $error .= '<p>Probleme de renseignement au niveau d\'un des champs, blaireau ! </p>';
            
        }
        
        if( $error == '')
        {

            $dbh = connexion();


            $login = $dbh->prepare('SELECT `aut_id`, `aut_name`, `aut_first_name`, `aut_password` FROM `bg_autor` WHERE `aut_email` = :mail ');

            $login->execute(['mail' => $tabPost['mail']]);

            $result = $login->fetch(PDO::FETCH_ASSOC);



            if($result !== false && password_verify( $tabPost['password'], $result['aut_password'] ))
            {
                
                $_SESSION['connect'] = true;
                $_SESSION['nom'] = $result['aut_name'];
                $_SESSION['prenom'] = $result['aut_first_name'];
                $_SESSION['auteur_id'] = $result['aut_id'];
                header('Location:listUsers.php');
               

            }
            else
            {
                $error .= 'Une erreur de login à eu lieu, veuillez recommencer';
            }
        }


    }
}


catch(PDOException $e)
{
    $error .= '<p>Une erreur de connexion a eu lieu'.$e->getMessage().'</p>';
}
catch(Exception $e)
{
    $errorException = '<p>Une erreur "d\'ordre générale" a eu lieu'.$e->getMessage().'</p>';
}


$vue = 'login.phtml';
$title = "login";
$lienActif = 'login';

include('tpl/layout.phtml');

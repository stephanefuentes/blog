<?php
session_start();


include('../config/config.php');
include('../lib/bdd.lib.php');

connectSession();




try
{
    if(!empty($_GET) && array_key_exists('id', $_GET))

    {        
        $dbh = connexion();

        $editUser = $dbh->prepare('SELECT  * FROM `bg_autor` WHERE `aut_id` = :id ');
       
        $editUser->execute( ['id'=>$_GET['id'] ]);
           
        $resulEditUser = $editUser->fetch(PDO::FETCH_ASSOC);

        $tabPost = [
            'nom'=>$resulEditUser["aut_name"],
            'prenom'=>$resulEditUser[ 'aut_first_name'],
            'mail'=>$resulEditUser[ 'aut_email'],
            'biographie'=>$resulEditUser[ 'aut_bio'],
            'avatar'=>$resulEditUser[ 'aut_avatar'],
            'pseudo'=>$resulEditUser[ 'aut_user_name'],
            'id'=>$resulEditUser[ 'aut_id']
        
        ];

    }

    


}


catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}
catch(Exception $e)
{
    $errorException = 'Une erreur "d\'ordre générale" a eu lieu'.$e->getMessage();
}




$vue = 'editUsers.phtml';
$title = "Edition utilisateur";
$lienActif ='';

include('tpl/layout.phtml');
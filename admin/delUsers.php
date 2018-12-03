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

        $nbreArticle = $dbh->prepare('SELECT art_title FROM  bg_article WHERE autor_fk_autor_id = :id ');

        if( $nbreArticle->execute(['id'=>$_GET['id'] ])== false)
        {
            $delUser =  $dbh->prepare('DELETE FROM `bg_autor` WHERE aut_id = :id ');

            $delUser->execute(['id'=>$_GET['id']]);


        }
        else
        {
            $_SESSION['error'] = '<p>L\'utilisateur est reliè a au moins un article, on ne peut pas le supprimer</p>';
           
        }

        

    }


}

catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}

header('Location:listUsers.php');
exit();
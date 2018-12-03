<?php
session_start();


include('../config/config.php');
include('../lib/bdd.lib.php');

connectSession();


try
{

    $dbh = connexion();

    $listUser = $dbh->prepare('SELECT  `aut_id`, `aut_name`, `aut_first_name`, `aut_email` FROM `bg_autor` ');


    $listUser->execute();

    $resultListUser = $listUser->fetchAll(PDO::FETCH_ASSOC);


}

catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}
catch(Exception $e)
{
    $errorException = 'Une erreur "d\'ordre générale" a eu lieu'.$e->getMessage();
}


$vue='listUsers.phtml';
$title = 'liste des utilisateur';
$lienActif = "listUser";

include('tpl/layout.phtml');
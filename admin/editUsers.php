<?php


include('../config/config.php');
include('../lib/bdd.lib.php');

try{

    $dbh = connexion();

    $listUser = $dbh->prepare('SELECT  `aut_name`, `aut_first_name` FROM  `aut_name` ');

    $listUser-> execute();

    $results = $listUser->fetchAll(PDO::FETCH_ASSOC);
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
$tittle = "fonction listing";

include('tpl/layout.phtml');
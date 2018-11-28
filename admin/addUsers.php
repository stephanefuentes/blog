<?php


include('../config/config.php');
include('../lib/bdd.lib.php');

$formulaireBienRempli = 1;
$bonMotDePasse = 1;
$mailValide = 1;



$tabPost = ['nom' => '', 'prenom' => '', 'mail' => '', 'bio' => '', 'avatar' => '', 'pseudo' => '' ];



if(array_key_exists('nom', $_POST))
{


    $tabPost = ['nom' => $_POST['nom'], 'prenom' => $_POST['prenom'], 'mail' => $_POST['mail'], 'bio' => $_POST['biographie'], 'avatar' => $_POST['avatar'], 'pseudo' => $_POST['pseudo'] ];

    try
    {

        foreach($_POST as $cle => $value)
        {
            if(trim($value))
            {

                echo '<p>Tous les champs n\'ont pas été correctement remplis</p>';
                $formulaireBienRempli = 0;
                break;
            }
            
        }


        if($_POST['password'] !== $_POST['confirmPassword'])
        {
            echo '<p>le mots de passe et la confirmation du mot de passe ne correspondent pas, blaireau !</p>';
            $bonMotDePasse = 0;

        }

       
        // si le mail n'est pas valide, renvoir False, sinon renvoie la string
        if ( !filter_var($_POST['mail'], 274) )
        {
            echo '<p>Le mail n\'est pas valide </p>';
            $mailValide = 0;
        }


        if( $formulaireBienRempli && $bonMotDePasse && $mailValide)
        {
            $dbh = connexion();

            $insertUser = $dbh->prepare('INSERT INTO `bg_autor`( `aut_name`, `aut_first_name`, `aut_email`, `aut_password`, `aut_bio`, `aut_avatar`, `aut_user_name`) VALUES (:nom , :prenom, :mail , :monpassword, :bio , :avatar, :pseudo)');
    
    
            $insertUser->execute(['nom' => $_POST['nom'], 'prenom' => $_POST['prenom'], 'mail' => $_POST['mail'], 'monpassword' => password_hash($_POST['password'],PASSWORD_BCRYPT), 'bio' => $_POST['biographie'], 'avatar' => $_POST['avatar'], 'pseudo' => $_POST['pseudo'] ]);
    
            echo ('<h1>Le formulaire a été bien transmie</h1>');

            $tabPost = ['nom' => $_POST['nom'], 'prenom' => $_POST['prenom'], 'mail' => $_POST['mail'], 'bio' => $_POST['biographie'], 'avatar' => $_POST['avatar'], 'pseudo' => $_POST['pseudo'] ];
    
    
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

      



}




$vue = 'addUsers.phtml';
$tittle = "fonction INSERT";

include('tpl/layout.phtml');






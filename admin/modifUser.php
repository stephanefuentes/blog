<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

connectSession();


$error = '';


try
{
    if(!empty($_POST))
    {

        $tabPost = [
            'nom'=>'',
            'prenom'=>'',
            'mail'=>'',
            'biographie'=>'',
            'avatar'=>'',
            'pseudo'=>'',
            'id'=>''
        
        ];

        foreach($tabPost as $champ => $value)
        {
            if(isset($_POST[$champ]) && trim($_POST[$champ])!='')
            {
                $tabPost[$champ]= $_POST[$champ];
            }
            else
            {
                $error = '<p>Au moins un champ a mal été renseigné</p>';
            }
            
            
        }

        // si le mail n'est pas valide, renvoir False, sinon renvoie la string
        if ( !filter_var($tabPost['mail'], FILTER_VALIDATE_EMAIL) )
        {
            $error .= '<p>Probleme de renseignement au niveau d\'un des champs, blaireau ! </p>';
            
        }

        if($error == '')
        {
            $dbh = connexion();

            $modifUser = $dbh->prepare('UPDATE bg_autor SET aut_name= :nom, aut_first_name= :prenom, aut_email = :mail, aut_bio= :biographie, aut_avatar = :avatar, aut_user_name = :pseudo WHERE aut_id = :id ');

            $modifUser->execute($tabPost);
            

            header('Location:listUsers.php');
            exit();
            //$resultCategorie = $categorie->fetchAll(PDO::FETCH_ASSOC);


        }


    }

}

catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}
// catch(Exception $e)
// {
//     $errorException = 'Une erreur "d\'ordre générale" a eu lieu'.$e->getMessage();
// }


$vue = 'editUsers.phtml';
$title = "Edition utilisateur";
$lienActif ='';

include('tpl/layout.phtml');
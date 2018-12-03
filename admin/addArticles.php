<?php
session_start();

include('../config/config.php');
include('../lib/bdd.lib.php');

connectSession();

$error ='';
$categorieId ='';


$tabPost = [
    'titre'=>'',
    'article'=>'',
    'tag'=>'',
    'dateA'=>'',
    'avatar'=>'',
    'auteurId'=>''
];

$tabPost['auteurId'] = $_SESSION['auteur_id'];




try
{
    
    $dbh = connexion();

    $categorie = $dbh->prepare('SELECT * FROM `bg_categorie` ');
    $categorie->execute();
    $resultCategorie = $categorie->fetchAll(PDO::FETCH_ASSOC);
    

    if( !empty($_POST) ) 
    {
        foreach( $tabPost as $champ => $value)
        {
            if(isset($_POST[$champ]) && $_POST[$champ]!= '' )
            {
                $tabPost[$champ] = $_POST[$champ];
            }
            else
            {
                $error = '<p><Formulaire mal renseigné/p>';
            }
        }


        if(!empty($_FILES))
        {
            $uploads_dir = '../upload/user';

            if($_FILES["avatar"]["error"] == UPLOAD_ERR_OK) 
            {
                $tmp_name = $_FILES["avatar"]["tmp_name"];
                    // basename() peut empêcher les attaques de système de fichiers;
                    // la validation/assainissement supplémentaire du nom de fichier peut être approprié
                    $name = basename($_FILES["avatar"]["name"]);
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");

                    $tabPost['avatar'] = $_FILES['avatar']['name'];
            }
            else
            {
                $error .= '<p><un problème sur le upload du fichier  eu lieu</p>';
            }
        }

        $tabPost['dateA'] = date('Y-m-d h:i:s');    
        // $date = date_create($_POST['date']);

        // $tabPost =['dateA'=>date_format($date, 'Y-m-d h:i:s'), 'title'=>$_POST['titre'], 'containt'=>$_POST['article'], 'avatar'=>$_FILES["avatar"]["name"], 'tag'=>'toto', 'auteurId'=> $_SESSION['auteur_id'] ];
        
        

        $article = $dbh->prepare('INSERT INTO `bg_article`( `art_date`, `art_title`, `art_contain`, `art_picture`,  `art_tag`, `autor_fk_autor_id`) VALUES (:dateA , :titre, :article , :avatar, :tag, :auteurId )');

        $resultArticle = $article->execute($tabPost);


        /*  pour la table de correspondance */
        $categorieId = $_POST['categorie'];
        $articleId = $dbh->lastInsertId();// renvoie le dernier id effectué

       $articleHasCategorie = $dbh->prepare('INSERT INTO bg_article_has_bg_categorie (bg_article_id_article, bg_categorie_id_categorie) VALUES (:idArticle, :idCategorie) ');

       $articleHasCategorie->execute(['idCategorie'=> $categorieId, 'idArticle'=>$articleId]);


        
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

      
$vue = 'addArticle.phtml';
$title = "Ajout article";
$lienActif = "addArticles";

include('tpl/layout.phtml');



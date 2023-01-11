<?php
$dblogin = 'root';
$dbpwd = '';

try
{
    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', $dblogin, $dbpwd);
}

catch(Exception $e)
{
  die('Erreur BDD : '.$e->getMessage());
}

if (isset($_POST["valider"]))
{
    if ($_POST["id"] != "")
    {
     $reponse = $bdd->prepare('UPDATE utilisateur SET nom = "'.$_POST["nom"].'", prenom = "'.$_POST["prenom"].'", mail = "'.$_POST["mail"].'", mdp = "'.$_POST["mdp"].'" WHERE id='.$_POST['id']);
     $reponse->execute();
    }
    else
    {
        if ($_POST["mdp"] == $_POST["mdp2"])
        {
            $reponse = $bdd->prepare('INSERT INTO utilisateur (type, nom, prenom, actif, mail, mdp) VALUES ("élève", "'.$_POST["nom"].'", "'.$_POST["prenom"].'", "oui", "'.$_POST["mail"].'", "'.$_POST["mdp"].'")');
            $reponse->execute();  
        }
        else
        {
            echo 'Les deux mots de passe ne sont pas identique';
        }   
    }
}

$donnees['matiere'] = '';
$donnees['prof'] = '';
$donnees['horaire'] = '';
$donnees['mail'] = '';
$donnees['mdp'] = '';

if(isset($_GET['élève'])){

    //echo 'Jai un ID';

    $reponse = $bdd->prepare('SELECT * FROM utilisateur WHERE id = :id');
	$reponse->execute(array(':id' => $_GET['élève']));
    $donnees = $reponse->fetch();

    //var_dump($donnees);

    if(!isset($donnees['id'])){
        $donnees['matiere'] = '';
        $donnees['prof'] = '';
        $donnees['horaire'] = '';
        $donnees['mail'] = '';
        $donnees['mdp'] = '';

        echo 'AUCUN UTILISATEUR AVEC CET IDENTIFIANT';
    }
}


?>

<html>
<body>

        <form method="post">

        <input type="hidden" name="id" value="<?= $donnees['nom'] ; ?>" ;>
         <label> Entrez la matière </label> <br>
         <input type = "text" name = "nom" value="<?= $donnees['matiere'] ; ?>"> <br>
         <label> Entrez le professeur </label> <br>
         <input type = "text" name = "nom" value="<?= $donnees['prof'] ; ?>"> <br>
         <label> Entrez les horaires </label> <br>
         <input type = "prenom" name = "prenom"  value="<?= $donnees['horaires'] ; ?>"> <br>
         <label> Entrez votre email </label> <br>
         <input type = "email" name = "mail" value="<?= $donnees['email'] ; ?>"> <br>
         <label> Entrez votre mot de passe </label> <br>
         <input type = "password" name = "mdp" value="<?= $donnees['mdp'] ; ?>"> <br>
         <label> confirmez votre mot de passe </label> <br>
         <input type = "password" name = "mdp2" value="<?= $donnees['mdp'] ; ?>"> <br><br>
         <button type="submit" name="valider" value="oui">Valider</button>


         </form>
</label>
</body>
</html>
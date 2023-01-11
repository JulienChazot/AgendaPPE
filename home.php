<?php
    session_start();
    // var_dump($_SESSION['utilisateur']) ; 

//Bouton déconnexion
    if(isset($_GET['decon'])){
        SESSION_DESTROY() ;
        header('Location: index.php');
    }
//Connexion
    if(isset($_SESSION['utilisateur'])){

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
        //On défini nos variables
        $donnees['id'] = '';
        $donnees['nom'] = '';
        $donnees['prenom'] = '';
        $donnees['mail'] = '';
        $donnees['mdp'] = '';


        

        


        //Message pour les profs et l'administration pour prévenir les élèves des changements éventuels.
        if(($_SESSION['utilisateur']['type'] == "prof" or $_SESSION['utilisateur']['type'] == "administration") && !isset($_SESSION['messageOuverture'])){
          $warning = '
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">ATTENTION</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            
            <h5 class="modal-title" id="exampleModalLabel">Merci de bien vouloir transmettre les informations importantes à l\'administration rapidement.</h5>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
          </div>
        </div>
        </div>
          ';
        }else{
          $warning = '';
        }
        $_SESSION['messageOuverture'] = 'ok' ;

        
        if($_SESSION['utilisateur']['type'] == "élève"){
          $warning = '';
        }
        echo $warning ;





        //Ajout élève dans la BDD
        if(isset($_POST['formAjoutEleve'])){
        
            if ($_POST["mdp"] == $_POST["mdp2"])
            {
                $reponse = $bdd->prepare('INSERT INTO utilisateur (type, nom, prenom, actif, mail, mdp, promotion) VALUES ("élève", "'.$_POST["nom"].'", "'.$_POST["prenom"].'", "oui", "'.$_POST["mail"].'", "'.$_POST["mdp"].'", "'.$_POST["classe"].'")');
                $reponse->execute(); 
                header ('Location: home.php?operationReussie=ajoutEleve'); 
            }
            else
            {
                header ('Location: home.php?operationEchouee=ajoutEleve'); 
            }   
        }

       
    
if(isset($_GET['operationReussie']) && $_GET['operationReussie'] == 'ajoutEleve'){
        echo 'L\'élève à bien été ajouté !';
    }
if(isset($_GET['operationEchouee']) && $_GET['operationEchouee'] == 'ajoutEleve'){
        echo 'Les mots de passes ne sont pas identiques';
    }

//Ajout prof dans la BDD
    if(isset($_POST['formAjoutProf'])){
        
      if ($_POST["mdp"] == $_POST["mdp2"])
      {
          $reponse = $bdd->prepare('INSERT INTO utilisateur (type, nom, prenom, actif, mail, mdp) VALUES ("prof", "'.$_POST["nom"].'", "'.$_POST["prenom"].'", "oui", "'.$_POST["mail"].'", "'.$_POST["mdp"].'")');
          $reponse->execute(); 
          header ('Location: home.php?operationReussie=ajoutProf'); 
      }
      else
      {
          header ('Location: home.php?operationEchouee=ajoutProf'); 
      }   
  }
  if(isset($_GET['operationReussie']) && $_GET['operationReussie'] == 'ajoutProf'){
    echo 'Le professeur à bien été ajouté !';
}
if(isset($_GET['operationEchouee']) && $_GET['operationEchouee'] == 'ajoutProf'){
    echo 'Les mots de passes ne sont pas identiques';
}
//Ajout admin dans la BDD
    if(isset($_POST['formAjoutAdmin'])){
            
      if ($_POST["mdp"] == $_POST["mdp2"])
      {
          $reponse = $bdd->prepare('INSERT INTO utilisateur (type, nom, prenom, actif, mail, mdp) VALUES ("administration", "'.$_POST["nom"].'", "'.$_POST["prenom"].'", "oui", "'.$_POST["mail"].'", "'.$_POST["mdp"].'")');
          $reponse->execute(); 
          header ('Location: home.php?operationReussie=ajoutAdmin'); 
      }
      else
      {
          header ('Location: home.php?operationEchouee=ajoutAdmin'); 
      }   
    }
    if(isset($_GET['operationReussie']) && $_GET['operationReussie'] == 'ajoutAdmin'){
    echo 'L\'administrateur à bien été ajouté !';
    }
  if(isset($_GET['operationEchouee']) && $_GET['operationEchouee'] == 'ajoutAdmin'){
  echo 'Les mots de passes ne sont pas identiques';
  }

    
}
else
{
header('Location: home.php');
}
 ?>

<html>
<link rel="shortcut icon" type="image/png" href="favicon.png"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <head>
  <link rel="shortcut icon" type="image/png" href="favicon.png"/>
  <link rel="stylesheet" href="index.css" />
  </head>


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
// On défini les variables
$donnees['matiere'] = '';
$donnees['prof'] = '';
$donnees['horaire'] = '';
$donnees['mail'] = '';
$donnees['mdp'] = '';

if(isset($_GET['élève'])){

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
//On défini nos variables
$modale = '';
$donnees['id'] = '';
$donnees['nom'] = '';
$donnees['prenom'] = '';
$donnees['mail'] = '';
$donnees['mdp'] = '';
$donnees['promotion'] = '';
//Bouton ajout prof
if(isset($_GET['ajoutProf'])){
  $modale = '
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Création Professeur</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
    <form method="post">
    <input type="hidden" name="formAjoutProf" value="oui" ;>
    <input type="hidden" name="id" value="'.$donnees['id'].'" ;>
     <label> Entrez votre un nom </label> <br>
     <input type = "text" name = "nom" value="'.$donnees['nom'].'"> <br>
     <label> Entrez un prénom </label> <br>
     <input type = "prenom" name = "prenom"  value="'.$donnees['prenom'].'"> <br>
     <label> Entrez votre email </label> <br>
     <input type = "email" name = "mail" value="'.$donnees['mail'].'"> <br>
     <label> Entrez votre mot de passe </label> <br>
     <input type = "password" name = "mdp" value="'.$donnees['mdp'].'"> <br>
     <label> confirmez votre mot de passe </label> <br>
     <input type = "password" name = "mdp2" value="'.$donnees['mdp'].'"> <br><br>
     <button type="submit" name="valider" value="oui">Valider</button>

     </form>
          </label>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
    </div>
  </div>
</div>
</div>
  ';
}
//Bouton ajout élève
if(isset($_GET['ajoutEleve'])){
    $modale = '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Création Elève</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post">
      <input type="hidden" name="formAjoutEleve" value="oui" ;>
      <input type="hidden" name="id" value="'.$donnees['id'].'" ;>
       <label> Entrez votre un nom </label> <br>
       <input type = "text" name = "nom" value="'.$donnees['nom'].'"> <br>
       <label> Entrez un prénom </label> <br>
       <input type = "prenom" name = "prenom"  value="'.$donnees['prenom'].'"> <br>
       <label> Entrez la classe de l\'élève </label> <br>
       <input type = "text" name = "classe" value="'.$donnees['promotion'].'"> <br>
       <label> Entrez votre mail </label> <br>
       <input type = "email" name = "mail" value="'.$donnees['mail'].'"> <br>
       <label> Entrez votre mot de passe </label> <br>
       <input type = "password" name = "mdp" value="'.$donnees['mdp'].'"> <br>
       <label> confirmez votre mot de passe </label> <br>
       <input type = "password" name = "mdp2" value="'.$donnees['mdp'].'"> <br><br>
       <button type="submit" name="valider" value="oui">Valider</button>

       </form>
            </label>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
//Bouton ajout admin
if(isset($_GET['ajoutAdmin'])){
    $modale = '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Création Administrateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post">

      <input type="hidden" name="formAjoutAdmin" value="oui" ;>


      <input type="hidden" name="id" value="'.$donnees['id'].'" ;>
       <label> Entrez votre un nom </label> <br>
       <input type = "text" name = "nom" value="'.$donnees['nom'].'"> <br>
       <label> Entrez un prénom </label> <br>
       <input type = "prenom" name = "prenom"  value="'.$donnees['prenom'].'"> <br>
       <label> Entrez votre email </label> <br>
       <input type = "email" name = "mail" value="'.$donnees['mail'].'"> <br>
       <label> Entrez votre mot de passe </label> <br>
       <input type = "password" name = "mdp" value="'.$donnees['mdp'].'"> <br>
       <label> confirmez votre mot de passe </label> <br>
       <input type = "password" name = "mdp2" value="'.$donnees['mdp'].'"> <br><br>
       <button type="submit" name="valider" value="oui">Valider</button>

       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
//Bouton modification prof
if(isset($_GET['editionProf'])){
    $modale = '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Recherche Professeur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="get" action="creationprof.php">

            <select name="prof" id="cfcfcggcgjjcg" style="color: #0000FF; box-shadow: 8px 8px 12px #0000FF;"> 
        
            <option>Choisir un Professeur</option>
            ';

        $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "prof"');
        $reponse->execute();

        while($donnees = $reponse->fetch()){
            $modale .= '<option value="'.$donnees['id'].'">'.$donnees['nom'].' '. $donnees['prenom'].'</option>';
        }		
        
         $modale .= '   </select>
        
            <input type="submit" name="edit" value="Modifier" />
        

     



            <a href="creationprof.php">
            <input type="button" value="Créer">
            </a>
        
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
//Bouton modification élève
if(isset($_GET['editionEleve'])){
    $modale = '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Recherche Elève</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form method="get" action="creationeleve.php">

            <select name="élève" id="élève" style="color: #0000FF; box-shadow: 8px 8px 12px #0000FF;"> 
        
            <option>Choisir un Elève</option>
            ';

        $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "élève"');
        $reponse->execute();

        while($donnees = $reponse->fetch()){
            $modale .= '<option value="'.$donnees['id'].'">'.$donnees['nom'].' '. $donnees['prenom'].'</option>';
        }		


         $modale .= '   </select>
        
            <input type="submit" name="edit" value="Modifier" />
        









            
            <a href="creationeleve.php">
            <input type="button" value="Créer">
            </a>
        
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
//Bouton modification Admin
if(isset($_GET['editionAdmin'])){
    $modale = '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Recherche Administrateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="get" action="creationadministration.php">

      <select name="administration" id="élève" style="color: #0000FF; box-shadow: 8px 8px 12px #0000FF;"> 
  
      <option>Choisir un Administrateur</option>
      ';

  $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "administration"');
  $reponse->execute();

  while($donnees = $reponse->fetch()){
      $modale .= '<option value="'.$donnees['id'].'">'.$donnees['nom'].' '. $donnees['prenom'].'</option>';
  }		


   $modale .= '   </select>
  
      <input type="submit" name="edit" value="Modifier" />
  
      <a href="creationadministration.php">
      <input type="button" value="Créer">
      </a>
  
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>
    ';
}

?>


<body>
    <br></br>
    <form method="get">
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Professeur" name="ajoutProf" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Elève" name="ajoutEleve" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Administrateur" name="ajoutAdmin" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Recherche Professeur" name="editionProf" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Recherche Eleve" name="editionEleve" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Recherche Admin" name="editionAdmin" class="btn btn-primary">';?>
    </form>
  
</label>
</body>
<?php echo $modale ; ?>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#exampleModal').modal('show');
    });
</script>



























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

if(!isset($_POST['ajouter'])) $_POST['ajouter'] = '';
$modale = '';

if($_POST['ajouter'] == 'oui'){

echo "formulaire recu";
//Ecrire dans la base de données le nouveau cours

$debutCours = $_POST['debutCours'] ;
$finCours = $_POST['finCours'] ;
$cours = $_POST['cours'];
$prof = $_POST['prof'];
$promotion = $_POST['promotion'];

$reponse = $bdd->prepare("INSERT INTO disponibilité (debut, fin, cours, id_prof, promotion) VALUES ('$debutCours', '$finCours', '$cours', '$prof', '$promotion')");
$reponse->execute();
        
        //Je vais chercher les utilisateurs qui sont dans cette promotion > SELECT * FROM utilisateurs WHERE promotion = la promotion que je viens de récupérer dans la requête précédente
        $reponseModif = $bdd->prepare('SELECT * FROM utilisateur WHERE promotion = "'.$promotion.'"');
        $reponseModif->execute();        
        //Je fais un while sur les données récupérées (donc les élèves qui ont ce cours)
        while($donneesMail = $reponseModif->fetch()){

          //echo 'DEBUT = '.$donneesModif['debut'];
           $dest = $donneesMail['mail'];
           $sujet = 'Ajout d\'un cours le  ' . date("d / m / Y", strtotime($debutCours)) .  ' de '. $debutCours.' à '. $finCours;
$corp = 'Bonjour,
Vous aurez cours de ' .$cours.' le  '  . date("d / m / Y", strtotime($debutCours)) . ' de '. date("H:i", strtotime($debutCours)).' à '. date("H:i", strtotime($finCours)).' 
Merci de bien prendre en note.
La Vie Scolaire';
           $headers = 'From: administration@stdenis.com';
           mail($dest, $sujet, $corp, $headers);
           echo 'mail envoyé a '.$dest.' -';
        }
        header('location: home.php');
}

//Modification de l'horaire d'un cours
if(isset($_POST['idCours'])){

    if(isset($_POST['Modifier'])){

        //Information des élèves par mail
        //Je sais que c'est le cours idCours
        //Je vais avoir besoin de conna$itre son heure actuelle SELECT * FROM dispo WHERE idCours=$_POST
        //Je connais avec le retour de la base l'heure "d'avant" et avec les données $POST l'heure modifiée
        $idcour = $_POST['idCours'] ;
        $reponseModif = $bdd->prepare('SELECT * FROM disponibilité WHERE id = "'.$_POST['idCours'].'"');
        $reponseModif->execute();
        $donneesModif = $reponseModif->fetch(); //retourne l'heure concerné
        //var_dump($donneesModif);
        
        //Je vais chercher en base la promotion qui a le cours idCours > SELECT promotion FROM disponibilites WHERE id = idCours
        $idcour = $_POST['idCours'] ;
        $reponseModif = $bdd->prepare('SELECT promotion FROM disponibilité WHERE id = "'.$_POST['idCours'].'"');
        $reponseModif->execute();
        $donneesPromo = $reponseModif->fetch(); //prend la promotion concerné
        
        //Je vais chercher les utilisateurs qui sont dans cette promotion > SELECT * FROM utilisateurs WHERE promotion = la promotion que je viens de récupérer dans la requête précédente
        $reponseModif = $bdd->prepare('SELECT * FROM utilisateur WHERE promotion = "'.$donneesPromo['promotion'].'"');
        $reponseModif->execute();        
        //Je fais un while sur les données récupérées (donc les élèves qui ont ce cours)
        while($donneesMail = $reponseModif->fetch()){

          //echo 'DEBUT = '.$donneesModif['debut'];
           $dest = $donneesMail['mail'];
           $sujet = 'Changement d\'un cours du  ' . date("d / m / Y", strtotime($donneesModif['debut'])) .  ' de '. $donneesModif['debut'].' à '. $donneesModif['fin'];
$corp = 'Bonjour,
Le cours du  '  . date("d / m / Y", strtotime($donneesModif['debut'])) . ' de '. date("H:i", strtotime($donneesModif['debut'])).' à '. date("H:i", strtotime($donneesModif['fin'])). ' est déplacé au '. date("d / m / Y", strtotime($_POST['debutCours'])). ' de ' . date("H:i", strtotime($_POST['debutCours'])). ' à ' . date("H:i", strtotime($_POST['finCours'])). '
Merci de bien prendre en note.
La Vie Scolaire';
           $headers = 'From: administration@stdenis.com';
           mail($dest, $sujet, $corp, $headers);
           //echo 'mail envoyé a '.$dest.' -';
        }

        //Requete update where id = idCours
        $debutCours = $_POST['debutCours'] ;
        $finCours = $_POST['finCours'] ;
        $reponseModif = $bdd->prepare('UPDATE disponibilité SET debut = "'.$debutCours.'", fin = "'.$finCours.'" WHERE id="'.$_POST['idCours'].'"');
        $reponseModif->execute();

    }elseif(isset($_POST['Supprimer'])){

        //Information des élèves par mail
        //Je sais que c'est le cours idCours
        //Je vais avoir besoin de conna$itre son heure actuelle SELECT * FROM dispo WHERE idCours=$_POST
        //Je connais avec le retour de la base l'heure "d'avant" et avec les données $POST l'heure modifiée
        $idcour = $_POST['idCours'] ;
        $reponseModif = $bdd->prepare('SELECT * FROM disponibilité WHERE id = "'.$_POST['idCours'].'"');
        $reponseModif->execute();
        $donneesModif = $reponseModif->fetch(); //retourne l'heure concerné
        //var_dump($donneesModif);
        
        //Je vais chercher en base la promotion qui a le cours idCours > SELECT promotion FROM disponibilites WHERE id = idCours
        $idcour = $_POST['idCours'] ;
        $reponseModif = $bdd->prepare('SELECT promotion FROM disponibilité WHERE id = "'.$_POST['idCours'].'"');
        $reponseModif->execute();
        $donneesPromo = $reponseModif->fetch(); //prend la promotion concerné
        
        //Je vais chercher les utilisateurs qui sont dans cette promotion > SELECT * FROM utilisateurs WHERE promotion = la promotion que je viens de récupérer dans la requête précédente
        $reponseModif = $bdd->prepare('SELECT * FROM utilisateur WHERE promotion = "'.$donneesPromo['promotion'].'"');
        $reponseModif->execute();        
        //Je fais un while sur les données récupérées (donc les élèves qui ont ce cours)
        while($donneesMail = $reponseModif->fetch()){

          //echo 'DEBUT = '.$donneesModif['debut'];
           $dest = $donneesMail['mail'];
           $sujet = 'Suppression du cours du  ' . date("d / m / Y", strtotime($donneesModif['debut'])) .  ' de '. $donneesModif['debut'].' à '. $donneesModif['fin'];
$corp = 'Bonjour,
Le cours du  '  . date("d / m / Y", strtotime($donneesModif['debut'])) . ' de '. date("H:i", strtotime($donneesModif['debut'])).' à '. date("H:i", strtotime($donneesModif['fin'])). ' est annuler.
Merci de bien prendre en note.
La Vie Scolaire';
           $headers = 'From: administration@stdenis.com';
           mail($dest, $sujet, $corp, $headers);
           echo 'mail envoyé a '.$dest.' -';
        }

        $reponseModif = $bdd->prepare('DELETE FROM disponibilité WHERE id="'.$_POST['idCours'].'"');
        $reponseModif->execute();

    }

}
//Modification cours
if(isset($_GET['modif']) && $_SESSION['utilisateur']['type'] == "administration"){

  $reponseModif = $bdd->prepare('SELECT * FROM disponibilité  WHERE id = "'.$_GET['modif'].'"');
  $reponseModif->execute();
  $donneesModif = $reponseModif->fetch();

  $modale =  '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification du cours : '.$donneesModif['cours'].'</h5>
        
      </div>
      <form method="post" action="home.php">
      <div class="modal-body">

      <input id="party" type="hidden" name="idCours" value="'.$_GET['modif'].'">  

      <label for="party">début de votre cours</label>
      <input id="party" type="datetime-local" name="debutCours" value="'.date("Y-m-d\TH:i", strtotime($donneesModif['debut'])).'">       
      <label for="party2">fin de votre cours </label>
      <input id="party2" type="datetime-local" name="finCours" style="margin: 3px"  value="'.date("Y-m-d\TH:i", strtotime($donneesModif['fin'])).'">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" name="Supprimer" value="oui" class="btn btn-primary">Supprimer</button>
        <button type="submit" name="Modifier" value="oui" class="btn btn-primary">Modifier</button>
      </div>
      </form>
    </div>
  </div>
</div>';

  
  
  }

?>


  <head>
    <meta charset='utf-8' />
    <link href='fullcalendar-scheduler/main.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <script src='fullcalendar-scheduler/main.js'></script>
    <script>

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var Calendar = FullCalendar.Calendar;
  

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    initialDate: '<?php echo date('Y-m-d') ; ?>',
    nowIndicator: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: [
      <?php
      //Voir chacun son emploi du temps
      if ($_SESSION['utilisateur']['type'] == "administration"){
        $reponse = $bdd->prepare('SELECT * FROM disponibilité');
        $reponse->execute();
      }
      if ($_SESSION['utilisateur']['type'] == "prof"){
        $reponse = $bdd->prepare('SELECT * FROM disponibilité WHERE id_prof = '.$_SESSION['utilisateur']['id']);
        $reponse->execute();
      }
      if ($_SESSION['utilisateur']['type'] == "élève"){
        $reponse = $bdd->prepare('SELECT * FROM disponibilité WHERE promotion = "'.$_SESSION['utilisateur']['promotion'].'"');
        $reponse->execute();
      }
        while($donnees = $reponse->fetch()){
      echo "{
        title: '".$donnees['cours']."',
        start: '".$donnees['debut']."',
        end: '".$donnees['fin']."',
        id: '".$donnees['id']."',
      },";
    }
    ?>
     
    ],

    eventClick: function(info) {
    info.jsEvent.preventDefault(); // don't let the browser navigate

        if (info.event.id) {
          window.open('?modif='+info.event.id, "_self");
        }
      }
  });

  calendar.render();
});

    </script>
  </head>
<body>


<div class="container">

  <div class="row">

    <div class="col-10">

      <div id='calendar' style=" height: 800px; margin: 3px; background-color: white"> </div>
      

    </div>

    <div class="col-2">
      
    <!-- Bouton création cours -->
    <?php if ($_SESSION['utilisateur']['type'] == "administration" or $_SESSION['utilisateur']['type'] == "prof") echo '<input type="submit" value="Création Cours" name="#AjouterModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AjouterModal">';?>

    </button>

    </div>

  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="AjouterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un cours </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="">


      <div class="modal-body">

        
          <label for="party">début de votre cours</label>
          <input id="party" type="datetime-local" name="debutCours">       
          <label for="party2">fin de votre cours </label>
          <input id="party2" type="datetime-local" name="finCours" style="margin: 3px";>

          <select class="form-select" aria-label="Default select example" name="cours" style="margin: 3px";>
            <?php
            //Base de donnée pour répertorier les cours
       $reponse = $bdd->prepare('SELECT DISTINCT cours FROM cours');
     $reponse->execute();

        while($donnees = $reponse->fetch()){
      echo "<option value='".$donnees['cours'] ."'>" .$donnees['cours'] ."</option>";
    }   
    ?>

            
          </select>

          <select class="form-select" aria-label="Default select example" name="promotion" style="margin: 3px";>
            <option selected>Classe
            <?php
       $reponse = $bdd->prepare('SELECT DISTINCT promotion, type FROM utilisateur WHERE type = "élève"');
     $reponse->execute();

        while($donnees = $reponse->fetch())
       {
         if ($donnees['promotion'] != '')
        {
          echo "<option>" .$donnees['promotion'] ."</option>";
        }

      }   
    ?>
            </option>
            
          </select>

          <select class="form-select" aria-label="Default select example" name="prof" style="margin: 3px";>
            <option selected>Prof
            <?php
       $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "prof"');
     $reponse->execute();

        while($donnees = $reponse->fetch()){
      echo "<option value='".$donnees['id']."'>" .$donnees['nom'] .' '. $donnees['prenom'] ."</option>";
    }   
    ?>

            </option>
            
          </select>
        

  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" name="ajouter" value="oui" class="btn btn-primary">Enregistrer</button>
      </div>

      </form>
    </div>
  </div>
</div>

      


<!-- Modal modif -->
<div class="modal fade" id="AjouterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajoutez un cours !</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="">


      <div class="modal-body">

        
          <label for="party">début de votre cours</label>
          <input id="party" type="datetime-local" name="debutCours">       
          <label for="party2">fin de votre cours </label>
          <input id="party2" type="datetime-local" name="finCours" style="margin: 3px";>

          <select class="form-select" aria-label="Default select example" name="cours" style="margin: 3px";>
            <option selected>Cours
            <?php
       $reponse = $bdd->prepare('SELECT DISTINCT cours FROM cours');
     $reponse->execute();

        while($donnees = $reponse->fetch()){
      echo "<option>" .$donnees['cours'] ."</option>";
    }   
    ?>





            </option>
            
          </select>

          <select class="form-select" aria-label="Default select example" name="promotion" style="margin: 3px";>
            <option selected>Classe
            <?php
       $reponse = $bdd->prepare('SELECT DISTINCT promotion, type FROM utilisateur WHERE type = "élève"');
     $reponse->execute();

        while($donnees = $reponse->fetch())
       {
         if ($donnees['promotion'] != '')
        {
          echo "<option>" .$donnees['promotion'] ."</option>";
        }

      }   
    ?>
            </option>
            
          </select>

          <select class="form-select" aria-label="Default select example" name="prof" style="margin: 3px";>
            <option selected>Prof
            <?php
       $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "id_prof"');
     $reponse->execute();

        while($donnees = $reponse->fetch()){
      echo "<option value='".$donnees['id']."'>" .$donnees['nom'] .' '. $donnees['prenom'] ."</option>";
    }   
    ?>

            </option>
            
          </select>
        

  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" name="ajouter" value="oui" class="btn btn-primary">Enregistrer</button>
      </div>

      </form>
    </div>
  </div>
</div>
  
<?php echo $modale ; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
 
    $(window).on('load',function(){
 
      $('#myModal').modal('show');
    });
 
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>



<form method="get">
        <input type="submit" name="decon" value="Déconnexion" class="btn btn-primary">
</form>

</html>
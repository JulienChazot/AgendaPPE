<?php
    session_start();

    if(isset($_GET['decon'])){
        SESSION_DESTROY() ;
        header('Location: index.php');
    }

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
        
        $donnees['id'] = '';
        $donnees['nom'] = '';
        $donnees['prenom'] = '';
        $donnees['mail'] = '';
        $donnees['mdp'] = '';
        
        if(isset($_POST['formAjoutEleve'])){
        
            if ($_POST["mdp"] == $_POST["mdp2"])
            {
                $reponse = $bdd->prepare('INSERT INTO utilisateur (type, nom, prenom, actif, mail, mdp) VALUES ("élève", "'.$_POST["nom"].'", "'.$_POST["prenom"].'", "oui", "'.$_POST["mail"].'", "'.$_POST["mdp"].'")');
                $reponse->execute(); 
                header ('Location: calendrier.php?operationReussie=ajoutEleve'); 
            }
            else
            {
                header ('Location: calendrier.php?operationEchouee=ajoutEleve'); 
            }   
        }
    
if(isset($_GET['operationReussie']) && $_GET['operationReussie'] == 'ajoutEleve'){
        echo 'L\'élève à bien été ajouté !';
    }
if(isset($_GET['operationEchouee']) && $_GET['operationEchouee'] == 'ajoutEleve'){
        echo 'Les mots de passes ne sont pas identiques';
    }
if(isset($_GET['operationReussie']) && $_GET['operationReussie'] == 'ajoutEleve'){
        echo 'L\'élève à bien été ajoutée !';
}

}else{
     header('Location: index.php');
} ?>

<html>
<link rel="shortcut icon" type="image/png" href="favicon.png"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <head>
  <link rel="shortcut icon" type="image/png" href="favicon.png"/>
  <link rel="stylesheet" href="index.css" />
  </head>
</html>

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
$modale = '';
$donnees['id'] = '';
$donnees['nom'] = '';
$donnees['prenom'] = '';
$donnees['mail'] = '';
$donnees['mdp'] = '';

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

      <input type="hidden" name="id" value="'.$donnees['id'].'" ;>
       <label> Entrez votre nom </label> <br>
       <input type = "text" name = "nom" value="'.$donnees['nom'].'";> <br>
       <label> Entrez votre prénom </label> <br>
       <input type = "prenom" name = "prenom"  value="'.$donnees['prenom'].'";> <br>
       <label> Entrez votre email </label> <br>
       <input type = "email" name = "mail" value="'.$donnees['mail'].'";> <br>
       <label> Entrez votre mot de passe </label> <br>
       <input type = "password" name = "mdp" value="'.$donnees['mdp'].'";> <br>
       <label> Confirmez votre mot de passe </label> <br>
       <input type = "password" name = "mdp2" value="'.$donnees['mdp'].'";> <br><br>
       <button type="submit" name="valider" value="oui">Valider</button>

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
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
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
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>
    ';
}
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

            <select name="élève" id="élève" style="color: #0000FF; box-shadow: 8px 8px 12px #0000FF;"> 
        
            <option>Choisir un Professeur</option>
            ';

        $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "élève"');
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

      <select name="élève" id="élève" style="color: #0000FF; box-shadow: 8px 8px 12px #0000FF;"> 
  
      <option>Choisir un Administrateur</option>
      ';

  $reponse = $bdd->prepare('SELECT id, nom, prenom FROM utilisateur WHERE type = "élève"');
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

<html>
<body>
    <br></br>
    <form method="get">
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Professeur" name="ajoutProf" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Elève" name="ajoutEleve" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Création Administrateur" name="ajoutAdmin" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration" or "prof") echo '<input type="submit" value="Recherche Professeur" name="editionProf" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration" or "prof") echo '<input type="submit" value="Recherche Eleve" name="editionEleve" class="btn btn-primary">';?>
    <?php if ($_SESSION['utilisateur']['type'] == "administration") echo '<input type="submit" value="Recherche Admin" name="editionAdmin" class="btn btn-primary">';?>
    </form>

    
    <form method="get">
        <input type="submit" name="decon" value="Déconnexion" class="btn btn-primary">
    </form>
   
</label>

<?php echo $modale ; ?>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#exampleModal').modal('show');
    });
</script>

</body>
</html>
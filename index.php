<?php
session_start();

$dblogin = 'root';
$dbpwd = '';
$message = '';

try{
    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', $dblogin, $dbpwd);
}catch(Exception $e)
{
    die('Erreur BDD : '.$e->getMessage());
}

if(isset($_POST['username'])){

    $reponse = $bdd->prepare('SELECT * FROM utilisateur WHERE mail = :mail AND mdp = :mdp AND actif = "oui"');
    $reponse->bindParam(':mail', $_POST['username']);
    $reponse->bindParam(':mdp', $_POST['password']);
    $reponse->execute();

    $donnees = $reponse->fetch() ;

    if(isset($donnees['id'])){
      $_SESSION['utilisateur'] = $donnees ;
      if(isset($_POST['remember'])) {
        setcookie('remember', $_POST['username']);
      }
      header('Location: home.php');
    }else{
      $message  = '<span class="messageAlerte">Aucun utilisateur actif avec ce username et ce password</span>';
      $_SESSION['username'] = $_SESSION['username'];
      $_SESSION['password'] = NULL;
    } 
}else{
  if(isset($_COOKIE['remember']))
      $_POST['username'] = $_COOKIE['remember'];
  else
    $_POST['username'] = '';
}
?>

<html>
  <head>
  <link rel="shortcut icon" type="image/png" href="favicon.png"/>
  <link rel="stylesheet" href="index.css" />
  <title>Connexion</title>
  </head>
  <body>
    <img src="Logo.png" style="box-shadow: 5px 5px 5px 5px black;">
    <div id="verif">
      <div class="texte"> Login </div>
      <form method="post" action="">
          <div class="sucess">
            <div>
  <br>
              <label for="username">Username</label>
              <input type="text" id="username" name="username" value="<?= $_POST['username'] ; ?>">
            </div>
  <br>
            <div>
              <label for="pass">Password</label>
              <input type="password" id="pass" name="password" minlength="6" required>
            </div>
            <label>
              <input type="checkbox" name="remember" value="0"/> Se souvenir de moi
            </label>
  <br>
            <?= $message ; ?>
  <br>
              <input type="submit" value="Connexion">      
  <br>
          </div>

        <div>
           
        </div>

    </form>
    </div>
  </body>
</html>
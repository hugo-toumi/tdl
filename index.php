<?php


require('connect.php');

if(!empty($_POST['pseudo'])&& !empty($_POST['email'])&& !empty($_POST['mdp'])&& !empty($_POST['mdp_confirm'])){

  $pseudo           = $_POST['pseudo']; 

  $mdp         = $_POST['mdp']; 

  $email        = $_POST['email']; 

  $mdp_confirm = $_POST['mdp_confirm'];
  
  
  if($mdp != $mdp_confirm){
    header('location: inscription.php?error=1&pass=1');
        exit();
  }

  $req = $db->prepare("SELECT count(*) as numberPseudo FROM membre WHERE pseudo = ?");
  $req->execute(array($pseudo));

  while($pseudo_verification = $req->fetch()){
    if($pseudo_verification['numberPseudo'] != 0){
      header('location: index.php?error=1&pseudo=1');
           exit();
    }
  }
  $req = $db->prepare("INSERT INTO membre (pseudo, email, mdp) VALUES(?,?,?)");
		$value = $req->execute(array($pseudo, $email, $mdp));
			
		header('location: index.php?success=1');
		exit();

}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="default.css">
    <title>Module de Connexion</title>
</head>
<body>
    <header>
      <h1>Inscription</h1>
    </header>

    <div class="container">

    <?php
     
     if(!isset($_SESSION['connect'])){ ?>

  

    <p id="info">Bienvenue sur mon site pour en voir plus, inscrivez vous. Sinon <a href="index.php">Connectez-vous</a><p>

    <?php
   
    if(isset($_GET['error'])){
    if(isset($_GET['pass'])){
      echo' <p id="error">Les mots de passe ne sont pas identiques.</p>';
    }
    else if(isset($_GET['pseudo'])){
       echo '<p id="error">Ce nom d utilisateur est deja pris.</p>';
    }
    }
    else if(isset($_GET['success'])){
      echo '<p id="success">Inscription prise correctement en compte.</p>';
    }
    
    ?>

    <div id="form">
    <form action="" method="post">
    <table>
    <tr>
    <td>Pseudo</td>
    <td><input type="texte" name="pseudo"
    placeholder="Ex : Hugo"required></td>
    </tr>
    <tr>
    <td>Email</td>
    <td><input type="texte" name="email"
    placeholder="Ex : hugo@yahoo.com"required></td>
    </tr>
    <tr>
    <td>mdp</td>
    <td><input type="password" name="mdp"
    placeholder="Ex : *****"required></td>
    </tr>
    <tr>
    <td>mdp_confirm</td>
    <td><input type="password" name="mdp_confirm"
    placeholder="Ex : *****"required></td>
    </tr>
    </table>
    <div id="button">
    <button>S'inscrire</button>
    </div>
     </form>
</div>
<?php }else {?>

  <p id="info">Bonjour <?php echo $_SESSION['pseudo'];?></p>

<?php } ?>
</div>
<?php

session_start();


$db = new PDO('mysql:host=localhost;dbname=tdl;charset=utf8', 'root','');

if(isset($_SESSION['connect'])){
  header('location: inscription.php');
}

require('connect.php');

if(!empty($_POST['login']) && !empty($_POST['mdp'])){

	// VARIABLES
	$login 		= $_POST['login'];
	$mdp 	= $_POST['mdp'];
	$error		= 1;

	
	

	$req = $db->prepare('SELECT * FROM utilisateurs WHERE login = ?');
	$req->execute(array($login));

	while($utilisateurs = $req->fetch()){

		if($mdp == $utilisateurs['mdp']){
			$error = 0;
			$_SESSION['connect'] = 1;
			$_SESSION['pseudo']	 = $utilsateurs['pseudo'];
			$_SESSION['id']	 = $utilsateurs['id'];
			
			
            $iduser = $utilisateurs['id'];
			header("location: tdl.html");

			if($_POST['login'] == 'admin'){
				header('Location: admin.php');
				}
			
		}


	}
	

	if($error == 1){
		header('location: tdl.html?error=1');
		
		
	}
    

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connexion</title>
	<link rel="stylesheet" type="text/css" href="default.css">
</head>
<body>
	<header>
		<h1>Connexion</h1>
	</header>

	<div class="container">
		<p id="info">Bienvenue sur mon site si vous n'êtes pas inscrit, <a href="index.php">inscrivez-vous.</a></p>
	 	
		<?php
			if(isset($_GET['error'])){
				echo'<p id="error">Nous ne pouvons pas vous authentifier.</p>';
			}
			else if(isset($_GET['success'])){
				echo'<p id="success">Vous êtes maintenant connecté.</p>';
			}
		?>

	 	<div id="form">
			<form method="POST" action="tdl.html">
				<table>
					<tr>
						<td>Pseudo</td>
						<td><input type="texte" name="login" placeholder="Ex : Nom d'utilisateurs" required></td>
					</tr>
					<tr>
						<td>Mot de passe</td>
						<td><input type="password" name="mdp" placeholder="Ex : ********" required ></td>
					</tr>
				</table>
				<p><label><input type="checkbox" name="connect" checked>Connexion automatique</label></p>
				<div id="button">
					<button type='submit'>Connexion</button>
				</div>
			</form>
		</div>
	</div>
</body>
<footer>

        <section class="part-two">
            <article class="social">
                <ul>
                    <a href="https://github.com/hugo-toumi"><img src="https://img.icons8.com/ios-filled/50/000000/github.png"/></a>
                    <a href="https://hugo-toumi.students-laplateforme.io/"><img src="https://img.icons8.com/ios-filled/50/000000/user-male-circle.png"/></a>
                    <a href="https://www.linkedin.com/in/hugo-magnani-13b91b1b5/"><img src="https://img.icons8.com/ios-filled/50/000000/linkedin-circled--v1.png"/></a>
                </ul>
            </article>
 </footer>                   
</html>    
</body>
</html>
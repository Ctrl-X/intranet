<?php
	include('Brain.php'); 
	$Brain->beforeLoad(array(
		'log'		=> array('request' => false, 'redirect' => 'index.php'),	// nécessite que l'utilisateur soit connecté ou pas
		'params'	=> array('request' => false)	// liste des paramètres nécessaires à l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>login</title>
		<link type="text/css" rel="stylesheet" href="css/main.css" />
	</head>
	
	<body>
		<div id="container">
			<?php $Brain->printError('signup'); ?>
			<form method="post" action="?MODULE=Brain&ACTION=signup">
				<ul>
					<li>
						<label for="prenom">Prénom : </label>
						<input type="text" name="prenom" value="<?php if(isset($_REQUEST['prenom']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['prenom']; ?>" />
					</li>
					
					<li>
						<label for="nom">Nom : </label>
						<input type="text" name="nom" value="<?php if(isset($_REQUEST['nom']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['nom']; ?>" />
					</li>
					
					<li>
						<label for="email">Email : </label>
						<input type="text" name="email" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['email']; ?>" />
					</li>
					
					<li>
						<label for="pass">Mot de passe : </label>
						<input type="password" name="pass" value="<?php if(isset($_REQUEST['pass']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['pass']; ?>" />
					</li>
					
					<li>
						<label for="statut">Statut : </label>
						<input type="radio" name="statut" value="étudiant" checked="checked" /> étudiant
						<input type="radio" name="statut" value="intervenant" /> intervenant
						<input type="radio" name="statut" value="administration" /> administration
					</li>
					
					<li>
						<label for="classe">Classe : </label>
						<select name="classe">
							<option value="préparatoire">préparatoire</option>
							<option value="DG1">Design Graphique 1</option>
							<option value="DG2">Design Graphique 2</option>
							<option value="DG3">Design Graphique 3</option>
							<option value="DG4">Design Graphique 4</option>
							<option value="WD">WebDesigner</option>
							<option value="WD Alt">WebDesigner Alt</option>
							<option value="Infographiste">Infographiste</option>
							<option value="Infographiste Alt">Infographiste Alt</option>
						</select>
					</li>
					
					<li>
						<input type="submit" value="inscription" />
					</li>
				</ul>
			</form>
			
			<form method="post" action="?MODULE=Brain&ACTION=signin">
				<ul>
					<li><label for="email">Email : </label></li>
					<li><input type="text" name="email" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signin') echo $_REQUEST['email']; ?>" /></li>
					
					<li><label for="pass">Mot de passe : </label></li>
					<li><input type="password" name="pass" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signin') echo $_REQUEST['pass']; ?>" /></li>
					
					<li><input type="submit" value="connexion" /></li>
				</ul>
			</form>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
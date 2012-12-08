<?php
	include('_boot/Brain.php'); 
	$Brain->beforeLoad(array(
		'log'		=> array('request' => false, 'redirect' => 'index.php'),	// nécessite que l'utilisateur soit connecté ou pas
		'params'	=> array('request' => false)								// liste des paramètres nécessaires à l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>login</title>
		<?php include('_boot/head.php'); ?>
		<script type="text/javascript">
			$(document).ready(function(){
				Brain.init({ isotope : { parentSelector : "#container" } }); 
				
				$(".niceform li").click(function(){
					if($(this).html() != "étudiant")
						$(".classe").addClass("disable"); 
					else
						$(".classe").removeClass("disable"); 
				}); 
				
				Brain.check(
				{
					"prenom" : function(val)
					{
						return val.length < 3 ? false : true; 
					}, 
					"nom" : function(val)
					{
						return val.length < 3 ? false : true; 
					}, 
					"pass" : function(val)
					{
						return val.length < 6 ? false : true; 
					}
				}); 
				
				/*
				$("#nom").datepicker({
					showOtherMonths		: true, 
					selectOtherMonths	: true, 
					dateFormat			: "dd/mm/yy", 
					minDate				: 0, 
					maxDate				: "+6M" 
				}); 
				*/
			}); 
		</script>
	</head>
	
	<body class="pink">
		<div id="container">
			<div class="tile _3x1">
				<h3>l'Agora c'est quoi ?</h3>
				<div class="content">
					<p>
						L'Agora est un intranet créé par les WebDesigner 2012 de Supcréa ouvert exclusivement aux étudiants, intervenant et membre de l'administration de l'école. 
					</p>
					<p>
						L'objectif est d'apporter des outils de communication, de support de cours mais aussi de partager sur la vie étudante. 
					</p>
					<p>
						Pour s'inscrire sur l'Agora il est nécessaire de se munir du <strong>code d'accès</strong>, distribué à l'école. 
					</p>
				</div>
			</div>
			<div class="tile _1x1">
				<h3>besoin d'aide ?</h3>
				<div class="content">
					<ul class="tips">
						<li class="support"><a href="#">mot de passe oublié</a></li>
						<li class="support"><a href="#">compte inactif</a></li>
						<li class="talk"><a href="#">reporter un bug</a></li>
						<li class="talk"><a href="#">contacter les admin</a></li>
						<li class="participe"><a href="#">créer un module pour l'Agora</a></li>
					</ul>
				</div>
			</div>
			
			<div id="signin" class="tile _1x1">
				<?php $Brain->printError('signin', '_1x1', 'erreur'); ?>
				
				<h3>connexion</h3>
				<div class="content">
					<form method="post" action="?MODULE=Brain&ACTION=signin&CONTEXT=data">
						<ul>
							<li><label for="email"><span class="required">*</span> Email : </label></li>
							<li><input placeholder="email@domain.fr" class="text" type="text" name="email" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signin') echo $_REQUEST['email']; ?>" /></li>
							
							<li><label for="pass"><span class="required">*</span> Mot de passe : </label></li>
							<li><input placeholder="mot de passe" class="password" type="password" name="pass" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signin') echo $_REQUEST['pass']; ?>" /></li>
							
							<li><input type="submit" value="connexion" /></li>
						</ul>
					</form>
				</div>
			</div>
			
			<div id="signup" class="tile _3x1">
				<?php $Brain->printError('signup', '_2x1', 'oups, certaines informations sont incorrects'); ?>
				
				<h3>inscription</h3>
				<div class="content">
					<form method="post" action="?MODULE=Brain&ACTION=signup&CONTEXT=data" enctype="multipart/form-data">
						<ul>
							<li><label for="prenom"><span class="required">*</span> Prénom : </label></li>
							<li><input placeholder="prénom" class="text" type="text" id="prenom" name="prenom" value="<?php if(isset($_REQUEST['prenom']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['prenom']; ?>" /></li>
							
							<li><label for="nom"><span class="required">*</span> Nom : </label></li>
							<li><input placeholder="nom" class="text" type="text" id="nom" name="nom" value="<?php if(isset($_REQUEST['nom']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['nom']; ?>" /></li>
							
							<li><label for="email"><span class="required">*</span> Email : </label></li>
							<li><input placeholder="email@domain.fr" class="text" type="text" name="email" value="<?php if(isset($_REQUEST['email']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['email']; ?>" /></li>
							
							<li><label for="pass"><span class="required">*</span> Mot de passe : </label></li>
							<li><input placeholder="mot de passe" class="password" type="password" id="pass" name="pass" value="<?php if(isset($_REQUEST['pass']) && isset($_REQUEST['ACTION']) && $_REQUEST['ACTION'] == 'signup') echo $_REQUEST['pass']; ?>" /></li>
							
							<!--<li><label for="statut">Statut : </label></li>-->
							<li class="colspan">
								<ul class="niceform statut">
									<li>
										<label>étudiant</label>
										<input type="radio" name="statut" value="etudiant" />
									</li>
									<li>
										<label>intervenant</label>
										<input type="radio" name="statut" value="intervenant" />
									</li>
									<li>
										<label>administration</label>
										<input type="radio" name="statut" value="administration" />
									</li>
								</ul>
							</li>
							
							<li class="classe"><label for="classe"><span class="required">*</span> Classe : </label></li>
							<li class="classe"><?php $Brain->getClasseForm('classe'); ?></li>
							
							<li class="colspan" style="overflow:hidden;">
								<label for="portrait">Image de profil : </label>
								<input style="max-width:180px;" type="file" name="portrait" />
							</li>
							
							<li><input placeholder="code accès" class="password" type="text" name="code" value="" /></li>
							<li><input type="submit" value="inscription" /></li>
							<!--<div class="clear"></div>-->
						</ul>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
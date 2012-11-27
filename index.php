<?php
	include('Brain.php'); 
	$Brain->beforeLoad(array(
		'log'		=> array('request' => true),	// nécessite que l'utilisateur soit connecté ou pas
		'params'	=> array('request' => false)	// liste des paramètres nécessaires à l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>holder</title>
		<link type="text/css" rel="stylesheet" href="css/main.css" />
		<link type="text/css" rel="stylesheet" href="css/isotope.css" />
		<script type="text/javascript" src="lib/jquery.min.js"></script>
		<script type="text/javascript" src="lib/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="lib/Brain.js"></script>
		
		<script type="text/javascript">
			Brain.selectData(
			{
				"utilisateur" : ["nom", "prenom"]
			}, null, function(result){
				for(i = 0; i < result.length; i++)
					for(key in result[i])
						console.log(result[i][key]); 
			}); 
			
			Brain.getModule({
				"MODULE" : "Brain", 
				"ACTION" : "test"
			}, function(result){
				// $("body").append(result); 
			}); 
			
			$(document).ready(function(){
				Brain.confirm("#confirm", "u sure ??"); 
			}); 
		</script>
	</head>
	<body>
		<?php
			$Brain->generatePass(); 
			// $Brain->signal('utilisateur', 'id_utilisateur', 1); 
			// $Brain->getClasseForm('select', 'lenom', true); 
			// $Brain->getClasseSelect(); 
			// $Brain->printModule(); 
		?>
		<a id="confirm" href="http://google.com">here we go</a>
		<div id="container">
			<div id="leftcolumn">
			
				<div class="tile _1x1 profil">
					<img src="images/portrait/def.gif" alt="" title="" width="120" height="120" />
					
					<div class="infos">
						Marcel Dupont - 23 ans - WebDesign - Etudiant - Modérateur
					</div>
					
					<ul class="links">
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
					</ul>
				</div>
				
				<ul class="menu">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<div class="clear"></div>
				</ul>
			</div>
			
			<div id="maincolumn">
				<div class="tile _1x3">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _2x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x2">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
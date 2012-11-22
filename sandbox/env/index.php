<?php
	include('pack/Brain.php'); 
	include('pack/emule.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Sandbox : environnement de développement</title>
		<link type="text/css" rel="stylesheet" href="pack/css/main.css" />
		<link type="text/css" rel="stylesheet" href="pack/css/control.css" />
		<link type="text/css" rel="stylesheet" href="pack/css/isotope.css" />
		<script type="text/javascript" src="pack/lib/jquery.min.js"></script>
		<script type="text/javascript" src="pack/lib/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="pack/lib/Brain.js"></script>
		
		<!-- ajouter ses feuilles de style et ses fichiers javascript ici : -->
		
		<!-- ************************************************************** -->
	</head>
	
	<body>
		<?php include('pack/control.php'); ?>
		<div id="container">
			<div id="leftcolumn">
			
				<div class="tile _1x1 profil">
					<img src="pack/images/def.gif" alt="" title="" width="120" height="120" />
					
					<div class="infos">
						<?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?> - 23 ans - <?php echo $_SESSION['classe']; ?> - <?php echo $_SESSION['statut']; ?> - <?php echo $_SESSION['rang']; ?>
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
				<?php $Brain->printModule(); ?>
				<div class="clear"></div>
			</div>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
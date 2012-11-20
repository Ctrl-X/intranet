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
	</head>
	
	<body>
		<a href="?MODULE=Brain&ACTION=disconnect">se déconnecter</a>
		<div id="container" style="position: relative; overflow: hidden; height: 240px; width:auto;" class="isotope">
			
			<?php
				$Brain->getClasseForm('select', 'lenom', true); 
				// $Brain->getClasseSelect(); 
				// $Brain->printModule(); 
			?>
			
			<div class="item" style="width:80px;height:80px;margin:8px;background-color:#dadada; position: absolute; -webkit-transform: translate3d(5px, 5px, 0px);"></div>
			<div class="item" style="width:176px;height:80px;margin:8px;background-color:#dadada; position: absolute; -webkit-transform: translate3d(5px, 5px, 0px);"></div>
			<div class="item" style="width:168px;height:176px;margin:8px;background-color:#dadada; position: absolute; -webkit-transform: translate3d(5px, 5px, 0px);"></div>
			<div class="item" style="width:80px;height:176px;margin:8px;background-color:#dadada; position: absolute; -webkit-transform: translate3d(5px, 5px, 0px);"></div>
			<div class="item" style="width:80px;height:80px;margin:8px;background-color:#dadada; position: absolute; -webkit-transform: translate3d(5px, 5px, 0px);"></div>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
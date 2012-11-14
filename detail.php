<?php
	include('Brain.php'); 
	$Brain->beforeLoad(array(
		'log' => array('request' => true),	// nécessite que l'utilisateur soit connecté ou pas
		'params' => array('request' => array('ACTION', 'MODULE'), 'redirect' => 'index.php')	// liste des paramètres nécessaires à l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>holder</title>
		<link type="text/css" rel="stylesheet" href="/campus.sc/style/main.css" />
	</head>
	
	<body>
		<div id="container">
			<?php
				$Brain->getDetailledModule(); 
			?>
		</div>
	</body>
</html>
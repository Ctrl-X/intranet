<?php
	include('Brain.php'); 
	$Brain->beforeLoad(array(
		'log'		=> array('request' => false),	// n�cessite que l'utilisateur soit connect� ou pas
		'params'	=> array('request' => false)	// liste des param�tres n�cessaires � l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>holder</title>
		<link type="text/css" rel="stylesheet" href="style/main.css" />
	</head>
	
	<body>
		<div id="container">
			<?php
				$Brain->printModule(); 
			?>
		</div>
	</body>
</html>
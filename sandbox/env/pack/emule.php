<?php 
	if(isset($_POST['cp-reset']) && $_POST['cp-reset'] == '1')
	{
		unset($_SESSION); 
		session_destroy(); 
	}
	
	if(!isset($_SESSION) || empty($_SESSION))
	{
		$utilisateur = $Brain->mysqlQuery('SELECT `id_utilisateur`, `prenom`, `utilisateur`.`nom`, `email`, `pass`, `statut`, `rang`, `date_inscription`, `derniere_connexion`, `hash`, `classe`.`code` AS `classe` FROM `utilisateur` LEFT JOIN `classe` ON `utilisateur`.`id_classe` = `classe`.`id_classe` ORDER BY RAND() LIMIT 1'); 
		$_SESSION = $utilisateur[0]; 
		
		/*
		$_SESSION = array(
			'id_utilisateur'		=> 1, 
			'prenom'				=> 'Marcel', 
			'nom'					=> 'Dupont', 
			'email'					=> 'marcel.dupont@email.fr', 
			'pass'					=> 'caf973c16410b87b3a996405f421ec14', 
			'statut'				=> 'étudiant', 
			'classe'				=> 'DG2', 
			'rang'					=> 'normal', 
			'date_inscription'		=> 1353243137, 
			'derniere_connexion'	=> 1353402757, 
			'hash'					=> '50a8da01ae4876.21831358'
		);
		*/
	}
	
	if(!empty($_POST))
	{
		$cpchamp = array(
			'cp-utilisateur'			=> 'utilisateur', 
			'cp-prenom'					=> 'prenom', 
			'cp-nom'					=> 'nom', 
			'cp-email'					=> 'email', 
			'cp-pass'					=> 'pass', 
			'cp-statut'					=> 'statut', 
			'cp-classe'					=> 'classe', 
			'cp-rang'					=> 'rang', 
			'cp-date_inscription'		=> 'date_inscription', 
			'cp-derniere_connexion'		=> 'derniere_connexion', 
			'cp-hash'					=> 'hash'
		); 
		
		foreach($cpchamp AS $key => $val)
			if(isset($_POST[$key]))
				$_SESSION[$val] = $_POST[$key]; 
	}
?>
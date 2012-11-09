<?php
	// la classe Brain est le moteur PHP principal
	class Cl_Brain
	{
		private $dbHost = "localhost"; 
		private $dbLogin = "root"; 
		private $dbPass = ""; 
		private $dbName = "agora"; 
		
		// fonction qui va installer les nouveaux modules
		function installModules ()
		{
			// par défaut les modules n'ont pas d'ordre : ils s'affichent dans l'ordre d'installation
			
		}
		
		// fonction qui récupère un module
		function getModule ()
		{
			$module = $this->mysqlQuery("SELECT `name`, `order` FROM `module` ORDER BY `installDate` ASC", "select"); 
			
			for($i = 0; $i < count($module); $i++)
				include('modules/' . $module[$i]["name"] . "/model.php"); 
		}
		
		// fonction de requete sql
		function mysqlQuery ($sql, $type = "select")
		{
			$query = mysql_query($sql) or die(mysql_error()); 
			
			if(strtolower($type) == "select")
			{
				$output = array(); 
				
				while($result = mysql_fetch_assoc($query))
					$output[] = $result; 
			}
			else
				$output = true; 
			
			return $output; 
		}
		
		// fonction de création d'une requête sql d'insertion
		function insertData ($queries)
		{
			// $Brain->insertData(array("table" => array( "nom" => "dupont", "age" => "18" ), "scndTable" => array( "champ" => "value" ))); 
			foreach($queries AS $tableName => $data)
			{
				$sqlStart = "INSERT INTO `$tableName` ("; 
				$sqlEnd = ""; 
				
				foreach($data AS $column => $val)
				{
					$sqlStart .= "`$column`, "; 
					$sqlEnd .= "'$val', "; 
				}
				
				$sql = substr($sqlStart, 0, -2) . ") VALUES " . substr($sqlEnd, 0, -2); 
				echo $sql; 
				// $this->mysqlQuery($sql); 
			}
		}
		
		// fonction de connexion à la base de donnée
		private function connectDb ()
		{
			mysqli_connect($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName); 
		}
		
		// fonction init de l'objet Brain
		function __construct ()
		{
			$this->connectDb(); 
		}
		
		// fonction qui nettoye les chaînes de caractères (html, caractères blancs, ...)
		function getCleanString ($str)
		{
		}
		
		// mise en forme d'un texte monoligne
		function setSentence ($sentence)
		{
		}
		
		// mise en forme d'un texte multiligne
		function setText ($text)
		{
		}
		
		// fonction qui vérifie la validité d'un nom / prénom
		function isValidName ($name)
		{
		}
		
		// fonction qui vérifie la validité d'une email
		function isValidEmail ($email)
		{
		}
		
		// fonction qui vérifie la validité d'un mot de passe
		function isValidPassword ($password)
		{
		}
		
		// formate un prénom
		function setFirstname ()
		{
		}
		
		// formate un nom
		function setLastname ()
		{
		}
		
		// formate une adresse email
		function setEmail ()
		{
		}
		
		// formate un mot de passe
		function setPassword ()
		{
		}
		
		// fonction qui envoi un email
		function sendEmail ($to, $message, $replyTo, $from)
		{
			/*
				to : destinataires du mail (tableau)
				message : le contenu du message
				replyTo : adresse mail de la personne à qui répondre
				from : adresse de l'expéditeur
			*/
		}
	}
	
	$Brain = new Cl_Brain(); 
?>

<!-- <script type="text/javascript" src="lib/Brain.js"></script> -->
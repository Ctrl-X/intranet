<?php
	// la classe Brain est le moteur PHP principal
	class Cl_Brain
	{
		private $dbHost = "localhost"; 
		private $dbLogin = "root"; 
		private $dbPass = ""; 
		private $dbName = "agora"; 
		
		// fonction de requete sql
		function mysqlQuery ($sql)
		{
		}
		
		// fonction de création d'une requête sql d'insertion
		function insertData ($queries)
		{
			for($queries AS $tableName => $data)
			{
				$sqlStart = "INSERT INTO `$tableName` ("; 
				$sqlEnd = ""; 
				
				for($data AS $column => $val)
				{
					$sqlStart .= "`$column`, "; 
					$sqlEnd .= "'$val', "; 
				}
				
				$sql = substr($sqlStart, 0, -2) . ") " . substr($sqlEnd, 0, -2); 
				$this->mysqlQuery($sql); 
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
		
		// fonction qui récupère un module
		function getModule ()
		{
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
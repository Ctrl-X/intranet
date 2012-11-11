<?php
	/*
		TODO : module pour dire quand quelqu'un sera à l'école (hors horaires de cours)
		TODO : js -> tester les affichages des blocs qui grossissent en cliquant sur [+]
	*/
	
	// importation des fonctions génériques non associés à un objet
	require_once("lib/lib.php"); 
	
	// la classe Brain est le moteur PHP principal
	class Brain
	{
		private $doNotInstall = array("SignIU", "UsefullLinks"); 
		
		private $mysqli = null; 
		private $dbHost = "localhost"; 
		private $dbLogin = "root"; 
		private $dbPass = ""; 
		private $dbName = "agora"; 
		
		// fonction qui va installer les nouveaux modules
		function installModules ()
		{
			// on commence par récupérer les modules qui sont déja installés
			$installedModules = $this->mysqlQuery("SELECT `name` FROM `module` ORDER BY `installDate` ASC", true); 
			
			// on ouvre le dossier des modules
			$modulesFolder = opendir("modules"); 
			
			// on parcourt chacun des sous dossiers
			while($moduleName = readdir($modulesFolder))
				// on test si les modules ne sont pas déjà installés
				// si ce n'est pas le cas on vérifie qu'ils possèdent les fichiers élémentaires
				if($moduleName != "." && $moduleName != ".." && is_dir('modules/' . $moduleName) && !in_array_r($moduleName, $installedModules))
				{
					if(in_array($moduleName, $this->doNotInstall))
						echo "<strong>$moduleName</strong> : fait partie de la blacklist<br/>"; 
					else
					{
						if(file_exists("modules/$moduleName/$moduleName.php"))
						{
							$moduleConfig = $this->getModule(array("MODULE" => $moduleName, "ACTION" => "install")); 
							
							$this->createTable($moduleConfig["table"]); 
							
							$this->insertData(array(
								'module' => array(
									'name' => $moduleName, 
									'position' => $moduleConfig['position'], 
									'script' => $moduleConfig['script'], 
									'style' => $moduleConfig['style'], 
									'installDate' => time()
								)
							)); 
							
							echo "<strong>$moduleName</strong> : installation complète<br/>"; 
						}
						else
							echo "<strong>$moduleName</strong> : le fichier <strong>$moduleName.php</strong> est manquant, sans ce fichier l'installation est impossible. Se référer à la documentation concernant l'implémentation d'un module.<br />"; 
					}
				}
		}
		
		// fonction qui va afficher les modules en tile
		function printModule ()
		{
			$modules = $this->selectData(array('module' => array('name', 'style', 'script')), '`installDate` ASC'); 
			
			for($i = 0; $i < count($modules); $i++)
			{
				$theModule = $modules[$i]; 
				
				$this->getModule(array('MODULE' => $theModule['name'], 'ACTION' => 'model', 'CONTEXT' => 'tile')); 
			}
		}
		
		// fonction qui récupère un module
		function getModule ($_COMMAND = null)
		{
			if($_COMMAND)
				return include('modules/' . $_COMMAND["MODULE"] . '/' . $_COMMAND["MODULE"] . '.php'); 
		}
		
		// fonction de requete sql
		function mysqlQuery ($sql, $return = false)
		{
			$query = $this->mysqli->query($sql); 
			
			if($query)
			{
				if($return)
				{
					$output = array(); 
					
					while($result = $query->fetch_assoc())
						$output[] = $result; 
					
					return $output; 
				}
			}
			else
				die("la requête demandé n'a pas aboutie pour : $sql"); 
			
			return true; 
		}
		
		// construction de la requête de création de table sql
		function createTable ($queries = null)
		{
			if($queries)
				foreach($queries AS $tableName => $champs)
				{
					$sql = 'CREATE TABLE ' . $tableName . ' ('; 
					
					foreach($champs AS $chName => $chType)
						$sql .= "$chName $chType, "; 
					
					$this->mysqlQuery(substr($sql, 0, -2) . ')'); 
				}
		}
		
		// création d'une requête select
		function selectData ($query, $order = null)
		{
			$sqlStart = "SELECT "; 
			$sqlEnd = ' FROM `' . key($query) . '`'; 
			
			foreach($query AS $table => $champ)
			{
				for($i = 0; $i < count($champ); $i++)
				{
					$sqlStart .= '`' . $champ[$i] . '`, '; 
				}
			}
			
			$sql = substr($sqlStart, 0, -2) . $sqlEnd; 
			
			if($order)
				$sql .= " ORDER BY $order"; 
			
			return $this->mysqlQuery($sql, true); 
		}
		
		// fonction de création d'une requête sql d'insertion
		function insertData ($queries = null)
		{
			if($queries)
				foreach($queries AS $tableName => $data)
				{
					$sqlStart = "INSERT INTO `$tableName` ("; 
					$sqlEnd = "("; 
					
					foreach($data AS $column => $val)
					{
						$sqlStart .= "`$column`, "; 
						$sqlEnd .= "'$val', "; 
					}
					
					$sql = substr($sqlStart, 0, -2) . ") VALUES " . substr($sqlEnd, 0, -2) . ")"; 
					$this->mysqlQuery($sql); 
				}
			else
				die("La requête d'insertion semble vide (pas de paramètres pour <strong>insertData()</strong>)"); 
		}
		
		// fonction de connexion à la base de donnée
		private function connectDb ()
		{
			$this->mysqli = new mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName); 
			$this->mysqli->set_charset('utf8'); 
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
	
	$Brain = new Brain(); 
	// $Brain->installModules(); 
?>

<!-- <script type="text/javascript" src="lib/Brain.js"></script> -->
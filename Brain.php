// <?php
	/*
		TODO : module pour dire quand quelqu'un sera à l'école (hors horaires de cours)
		TODO : js -> tester les affichages des blocs qui grossissent en cliquant sur [+]
		TODO : ajouter "limit" à la fonction selectData
	*/
	
	// importation des fonctions génériques non associés à un objet
	require_once('lib/lib.php'); 
	
	// la classe Brain est le moteur PHP principal
	class Brain
	{
		private $doNotInstall = array('Profile', 'UsefullLinks'); 
		
		private $mysqli = null; 
		private $dbHost = 'localhost'; 
		private $dbLogin = 'root'; 
		private $dbPass = ''; 
		private $dbName = 'agora'; 
		
		/**
		 * \brief Lance l'exécution de scripts avant le chargement de la page
		 * \details Vérifie si la page visité nécessite d'être connecté ou des paramètres ($_GET / $_POST)
		 * \param $request Un tableau du type array('log' => array('request' => true, 'redirect' => 'index.php'), 'params' => array('request' => array('MODULE', 'ACTION'), 'redirect' => 'index.php'))
		 * \return ne retourne rien, stop le chargement si le paramètre n'est pas passé
		 */
		function beforeLoad ($request = null)
		{
			if($request)
			{
				if($request['log']['request'] && (!isset($_SESSION) || empty($_SESSION)))
					header('Location: ' . (isset($request['log']['redirect']) ? $request['log']['redirect'] : 'login.php')); 
				
				if($request['params']['request'])
					for($i = 0; $i < count($request['params']['request']); $i++)
					{
						$key = $request['params']['request'][$i]; 
						
						if(!isset($_REQUEST[$key]))
							header('Location: ' . (isset($request['params']['redirect']) ? $request['params']['redirect'] : 'index.php')); 
					}
			}
			else
				die('tableau $request null pour la fonction beforeLoad'); 
		}
		
		// TOFIX : fonction utile ? 
		function watchParams () 
		{
			if(!empty($_REQUEST))
			{
				if(isset($_REQUEST['MODULE']))
				{
					$this->getModule(array(
						'MODULE' => $_REQUEST['MODULE'], 
						'ACTION' => isset($_REQUEST['ACTION']) ? $_REQUEST['ACTION'] : null, // peut valoir model ou data ou autres selon le module
						'CONTEXT' => isset($_REQUEST['CONTEXT']) ? $_REQUEST['CONTEXT'] : null, 
						'PARAMS' => $_REQUEST
					)); 
				}
			}
		}
		
		/**
		 * \brief Lance l'installation des nouveaux modules
		 * \details Vérifie quels sont les modules installés puis regarde dans le dossier 'modules' ceux qui ne le sont pas et les installe si le fichier d'installation existe (un fichier .php qui porte exactemement le même nom que son dossier). 
		 * \return ne retourne rien, affiche un message lorsqu'un fichier est manquant ou que le module fait partie de la blacklist
		 */
		function installModules ()
		{
			// on commence par récupérer les modules qui sont déja installés
			$installedModules = $this->mysqlQuery('SELECT `name` FROM `module` ORDER BY `installDate` ASC'); 
			
			// on ouvre le dossier des modules
			$modulesFolder = opendir('modules'); 
			
			// on parcourt chacun des sous dossiers
			while($moduleName = readdir($modulesFolder))
				// on test si les modules ne sont pas déjà installés
				// si ce n'est pas le cas on vérifie qu'ils possèdent les fichiers élémentaires
				if($moduleName != '.' && $moduleName != '..' && is_dir('modules/' . $moduleName) && !in_array_r($moduleName, $installedModules))
				{
					if(in_array($moduleName, $this->doNotInstall))
						echo "<strong>$moduleName</strong> : fait partie de la blacklist<br/>"; 
					else
					{
						if(file_exists("modules/$moduleName/$moduleName.php"))
						{
							$moduleConfig = $this->getModule(array('MODULE' => $moduleName, 'ACTION' => 'install')); 
							
							$this->createTable($moduleConfig['table']); 
							
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
		
		/**
		 * \brief Affiche un ensemble de module
		 * \details Sélectionne l'ensemble des modules dans la base de donnée selon les paramètres passés puis les appel via getModule()
		 * \param $params = array()
		 * \param 'position' conditionne la récupération des modules à la position qu'ils doivent avoir (modules ne s'affichant que sur l'accueil, modules ne s'affichant que sur la page profil, ...)
		 * \param 'context' fait partie des informations dont nécessite le module et peut être de différent type (tile, detail, ...). L'affichage varie selon le contexte
		 * \param 'limit' pour donner une limite à la récupération de ce module (les 5 derniers, ...)
		 * \return appel la fonction getModule() autant de fois que de modules demandés
		 */
		function printModule ($params = array( 'position' => 'normal', 'context' => 'tile', 'limit' => false))
		{
			// TODO : prévoir la condition WHERE et ORDER
			if(!isset($params['position']))
				$params['position'] = 'normal'; 
			if(!isset($params['context']))
				$params['context'] = 'tile'; 
			
			$modules = $this->selectData(array('module' => array('name', 'style', 'script')), array('order' => '`installDate` ASC', 'where' => '`position` = "' . $params['position'] . '"', 'limit' => isset($params['limit']) && $params['limit'] ? $params['limit'] : false)); 
			
			for($i = 0; $i < count($modules); $i++)
			{
				$theModule = $modules[$i]; 
				
				$this->getModule(array('MODULE' => $theModule['name'], 'ACTION' => 'model', 'CONTEXT' => $params['context'])); 
			}
		}
		
		/**
		 * \brief Récupère un module
		 * \details Appel un module en lui passant des commandes soit l'ACTION, le CONTEXT, ... Le module Brain gère ses actions à ce niveau (installation de module, ...)
		 * \param $_COMMAND = array()
		 * \return inclut un fichier php
		 */
		function getModule ($_COMMAND = null)
		{
			if($_COMMAND)
			{
				if($_COMMAND['MODULE'] == 'Brain')
					switch($_COMMAND['ACTION'])
					{
						case 'install' : $this->installModules(); break; 
					}
				else
					return include('modules/' . $_COMMAND['MODULE'] . '/' . $_COMMAND['MODULE'] . '.php'); 
			}
		}
		
		/**
		 * \brief Appel la version détaillé d'un module
		 * \details Récupère un module si des paramètres existent (ACTION, MODULE, CONTEXT) et lui passe l'ensemble des paramètres ($_GET / $_POST). 
		 * \return inclusion du module via getModule()
		 */
		function getDetailledModule ()
		{
			// print_r($_REQUEST); 
			if(isset($_REQUEST['MODULE']) && isset($_REQUEST['ACTION']) && $this->doesExist($_REQUEST['MODULE']))
			{
				$this->getModule(array(
					'MODULE' => $_REQUEST['MODULE'], 
					'ACTION' => $_REQUEST['ACTION'], 
					'CONTEXT' => isset($_REQUEST['CONTEXT']) ? $_REQUEST['CONTEXT'] : 'detail', 
					'PARAMS' => $_REQUEST
				)); 
			}
		}
		
		// fonction qui vérifie si un module existe et qu'il est installé
		function doesExist ($moduleName)
		{
			return count($this->selectData(array(
				'module' => array(
					'name'
				)
			), array(
				'where' => "`name` = '$moduleName'"
			))) > 0 ? true : false; 
		}
		
		// fonction de requete sql
		function mysqlQuery ($sql)
		{
			$query = $this->mysqli->query($sql); 
			
			if($query)
			{
				if(strpos($sql, 'SELECT') !== false)
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
		function selectData ($query, $params = null)
		{
			$sqlStart = 'SELECT '; 
			$sqlEnd = ' FROM `' . key($query) . '`'; 
			
			foreach($query AS $table => $champ)
			{
				for($i = 0; $i < count($champ); $i++)
				{
					$sqlStart .= '`' . $champ[$i] . '`, '; 
				}
			}
			
			$sql = substr($sqlStart, 0, -2) . $sqlEnd; 
			
			if($params)
			{
				if(isset($params['where']) && $params['where'])
					$sql .= ' WHERE ' . $params['where']; 
				if(isset($params['order']) && $params['order'])
					$sql .= ' ORDER BY ' . $params['order']; 
				if(isset($params['limit']) && $params['limit'])
					$sql .= ' LIMIT ' . $params['limit']; 
			}
			
			return $this->mysqlQuery($sql); 
		}
		
		// fonction de création d'une requête sql d'insertion
		function insertData ($queries = null)
		{
			if($queries)
				foreach($queries AS $tableName => $data)
				{
					$sqlStart = "INSERT INTO `$tableName` ("; 
					$sqlEnd = '('; 
					
					foreach($data AS $column => $val)
					{
						$sqlStart .= "`$column`, "; 
						$sqlEnd .= "'$val', "; 
					}
					
					$sql = substr($sqlStart, 0, -2) . ') VALUES ' . substr($sqlEnd, 0, -2) . ')'; 
					$this->mysqlQuery($sql); 
				}
			else
				die('La requête d\'insertion semble vide (pas de paramètres pour <strong>insertData()</strong>)'); 
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
			// $this->watchParams(); 
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
			if(strlen($password) > 3)
				return true; 
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
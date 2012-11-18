<?php
	/*
		TODO : module pour dire quand quelqu'un sera à l'école (hors horaires de cours)
	*/
	
	// importation des fonctions génériques non associés à un objet
	require_once('lib/lib.php'); 
	
	// la classe Brain est le moteur PHP principal
	class Brain
	{
		public static $email = array('replyTo' => 'postmaster@agora-supcrea.fr', 'from' => 'postmaster@agora-supcrea.fr'); 
		
		private $doNotInstall = array('Profile', 'UsefullLinks'); 
		
		private $mysqli		= null; 
		private $dbHost 	= 'localhost'; 
		private $dbLogin 	= 'root'; 
		private $dbPass 	= ''; 
		private $dbName 	= 'agora'; 
		private $css		= array(); 
		private $js			= array(); 
		private $error		= array(); 
		
		
		/**
		 * \brief	Lance l'exécution de scripts avant le chargement de la page
		 * \details	Vérifie si la page visité nécessite d'être connecté ou des paramètres ($_GET / $_POST)
		 * \param	$request Un tableau du type array('log' => array('request' => true, 'redirect' => 'index.php'), 'params' => array('request' => array('MODULE', 'ACTION'), 'redirect' => 'index.php'))
		 * \return	ne retourne rien, stop le chargement si le paramètre n'est pas passé
		 */
		function beforeLoad ($request = null)
		{
			print_r($_SESSION); 
			if($request)
			{
				if($request['log']['request'] && (!isset($_SESSION) || empty($_SESSION)))
					header('Location: ' . (isset($request['log']['redirect']) ? $request['log']['redirect'] : 'login.php')); 
				else if(!$request['log']['request'] && isset($request['log']['redirect']) && isset($_SESSION) && !empty($_SESSION))
					header('Location: ' . $request['log']['redirect']); 
				
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
		
		function startCapture ()
		{
			ob_start(); 
		}
		
		function endCapture ()
		{
			$body = ob_get_clean(); 
			
			$fullCss = ''; 
			for($i = 0; $i < count($this->css); $i++)
				$fullCss .= '<link type="text/css" rel="stylesheet" src="' . $this->css[$i] . '" />'; 
			
			
			$fullJs = ''; 
			for($i = 0; $i < count($this->js); $i++)
				$fullJs .= '<script type="text/javascript" href="' . $this->js[$i] . '"></script>'; 
			
			$body = str_replace('</head>', $fullCss . $fullJs . '</head>', $body); 
			
			echo $body; 
		}
		
		/**
		 * \brief	Lance l'installation des nouveaux modules
		 * \details	Vérifie quels sont les modules installés puis regarde dans le dossier 'modules' ceux qui ne le sont pas et les installe si le fichier d'installation existe (un fichier .php qui porte exactemement le même nom que son dossier). 
		 * \return	ne retourne rien, affiche un message lorsqu'un fichier est manquant ou que le module fait partie de la blacklist
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
							if($moduleConfig = $this->getModule(array('MODULE' => $moduleName, 'ACTION' => 'install')))
							{
								$this->createTable($moduleConfig['table']); 
								
								$this->insertData(array(
									'module' => array(
										'name' => $moduleName, 
										'position' => $moduleConfig['position'], 
										'script' => $moduleConfig['script'], 
										'css' => $moduleConfig['css'], 
										'installDate' => time()
									)
								)); 
								
								echo "<strong>$moduleName</strong> : installation complète<br/>"; 
							}
							else
								echo "<strong>$moduleName</strong> : n'est pas prêt à l'installation (action 'install' inatendue par le module')"; 
						}
						else
							echo "<strong>$moduleName</strong> : le fichier <strong>$moduleName.php</strong> est manquant, sans ce fichier l'installation est impossible. Se référer à la documentation concernant l'implémentation d'un module.<br />"; 
					}
				}
		}
		
		/**
		 * \brief	Affiche un ensemble de module
		 * \details	Sélectionne l'ensemble des modules dans la base de donnée selon les paramètres passés puis les appel via getModule()
		 * \param	$params = array()
		 * \param	'position' conditionne la récupération des modules à la position qu'ils doivent avoir (modules ne s'affichant que sur l'accueil, modules ne s'affichant que sur la page profil, ...)
		 * \param	'context' fait partie des informations dont nécessite le module et peut être de différent type (tile, detail, ...). L'affichage varie selon le contexte
		 * \param	'limit' pour donner une limite à la récupération de ce module (les 5 derniers, ...)
		 * \return	appel la fonction getModule() autant de fois que de modules demandés
		 */
		function printModule ($params = array( 'position' => 'normal', 'context' => 'tile', 'limit' => false, 'where' => false, 'order' => false))
		{
			if(!isset($params['position']))	$params['position']	= 'normal'; 
			if(!isset($params['context']))	$params['context']	= 'tile'; 
			if(!isset($params['limit']))	$params['limit']	= false; 
			if(!isset($params['where']))	$params['where']	= false; 
			if(!isset($params['order']))	$params['order']	= false; 
			
			$modules = $this->selectData(
				array(
					'module' => array(
						'name', 'css', 'script'
					)
				), array(
					'order' => $params['order'] ? $params['order'] : '`installDate` ASC', 
					'where' => $params['where'] ? $params['where'] : false, 
					'limit' => $params['limit'] ? $params['limit'] : false
				)
			); 
			
			for($i = 0; $i < count($modules); $i++)
			{
				$theModule = $modules[$i]; 
				
				if($this->getModule(array('MODULE' => $theModule['name'], 'ACTION' => 'model', 'CONTEXT' => $params['context']), true))
				{
					$this->addCss($theModule['name'], $theModule['css']); 
					$this->addJs($theModule['name'], $theModule['script']); 
				}
			}
		}
		
		function addCss ($moduleName, $css)
		{
			$css = explode(',', str_replace(' ', '', $css)); 
			for($i = 0; $i < count($css); $i++)
				$this->css[] = 'modules/' . $moduleName . '/' . $css[$i]; 
		}
		
		function addJs ($moduleName, $js)
		{
			$js = explode(',', str_replace(' ', '', $js)); 
				for($i = 0; $i < count($js); $i++)
					$this->js[] = 'modules/' . $moduleName . '/' . $js[$i]; 
		}
		
		/**
		 * \brief	Récupère un module
		 * \details	Appel un module en lui passant des commandes soit l'ACTION, le CONTEXT, ... Le module Brain gère ses actions à ce niveau (installation de module, ...)
		 * \param	$_COMMAND = array()
		 * \return	inclut un fichier php
		 */
		function getModule ($_COMMAND = null, $preloaded = false)
		{
			if($_COMMAND)
			{
				if($_COMMAND['MODULE'] == 'Brain')
					switch($_COMMAND['ACTION'])
					{
						case 'install' : $this->installModules(); break; 
						case 'signup' : $this->signUp(); break; 
						case 'activate' : $this->activeAccount(); break; 
						case 'signin' : $this->signIn(); break; 
						case 'disconnect' : $this->disconnect(); break; 
						default : return false; break; 
					}
				else if($preloaded || $theModule = $this->doesExist($_REQUEST['MODULE']))
				{
					if(!$preloaded)
					{
						$this->addCss($theModule['name'], $theModule['css']); 
						$this->addJs($theModule['name'], $theModule['script']); 
					}
					
					return include('modules/' . $_COMMAND['MODULE'] . '/' . $_COMMAND['MODULE'] . '.php'); 
				}
			}
		}
		
		function log ($text)
		{
			$fileName = 'log/' . date('d-M-Y', time()); 
			
			$name = isset($_SESSION['prenom']) && isset($_SESSION['nom']) ? $_SESSION['prenom'] . '_' . $_SESSION['nom'] : null; 
			
			file_put_contents($fileName . '.txt', '[' . date('G\hi', time()) . '] - ' . ($name ? $name . ' ' : '') . " : $text \r\n", FILE_APPEND); 
			
			if($name)
				file_put_contents($fileName . '-' . $name . '.txt', '[' . date('G\hi', time()) . "] : $text \r\n", FILE_APPEND); 
		}
		
		function printError ($from)
		{
			if(isset($this->error[$from]) && !empty($this->error[$from]))
				var_dump($this->error[$from]); 
		}
		
		function activeAccount ()
		{
			if(isset($_REQUEST['email']) && isset($_REQUEST['hash']))
			{
				if(count($utilisateur = $this->selectData(array(
					'utilisateur' => array('rang')
				), array(
					'where' => '`email` = \'' . $_REQUEST['email'] . '\' AND `hash` = \'' . $_REQUEST['hash'] . '\''
				))) > 0)
				{
					switch($utilisateur[0]['rang'])
					{
						case 'inactif' : 
							$this->updateData(array(
								'utilisateur' => array(
									'rang' => 'normal', 
									'where' => '`email` = \'' . $_REQUEST['email'] . '\' AND `hash` = \'' . $_REQUEST['hash'] . '\''
								)
							)); break; 
						case 'désactivé' : die('votre compte est désactivé'); break; 
						default : echo 'votre compte est déjà actif'; break; 
					}
				}
				else
					die('lien invalide'); 
			}
		}
		
		function disconnect ()
		{
			session_destroy(); 
			unset($_SESSION); 
		}
		
		function signIn ()
		{
			if(isset($_REQUEST['email']) && isset($_REQUEST['pass']))
			{
				if(count($user = $this->selectData(array(
					'utilisateur' => array('*')
				), array(
					'where' => '`email` = \'' . $_REQUEST['email'] . '\' AND `pass` = \'' . md5($_REQUEST['pass']) . '\''
				))) > 0)
				{
					$_SESSION = $user[0]; 
					$this->updateData(array(
						'utilisateur' => array(
							'derniere_connexion' => time(), 
							'where' => '`email` = \'' . $user[0]['email'] . '\'' 
						)
					)); 
					
					echo 'you just get logged in !'; 
				}
				else
					echo('email ou mot de passe incorrect'); 
			}
		}
		
		function signUp ()
		{
			if(!empty($_POST))
			{
				$pattern = array(
					'prenom' => 'isValidFirstname', 
					'nom' => 'isValidLastname', 
					'email' => 'isValidEmail', 
					'pass' => 'isValidPassword'
				); 
				
				foreach($pattern AS $key => $func)
				{
					$val = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null; 
					$$key = $this->$func($val, 'signup'); 
				}
				
				if(empty($this->error['signup']))
				{
					$user = $this->selectData(
						array(
							'utilisateur' => array(
								'email'
							)
						), array(
							'where' => "`email` = '$email'"
						)
					); 
					
					if(count($user) > 0)
						$this->error['signup']['email'] = 'Cette adresse email est déjà utilisé par un membre'; 
					else
					{
						if($this->insertData(array(
							'utilisateur' => array(
								'prenom' => $prenom, 
								'nom' => $nom, 
								'email' => $email, 
								'pass' => $pass, 
								'statut' => $_REQUEST['statut'], 
								'classe' => $_REQUEST['classe'], 
								'rang' => 'inactif', 
								'date_inscription' => time(), 
								'derniere_connexion' => 0, 
								'hash' => $hash = uniqid('', true) 
							)
						)))
						{
							Email::sendEmail ($email, 'Inscription sur agora.supcrea.fr', 
								array
								(
									'name' => 'signup', 
									'tofirstname' => $prenom, 
									'tolastname' => $nom, 
									'toemail' => $email
								)
							); 
						}
					}
				}
			}
		}
		
		/**
		 * \brief	Appel la version détaillé d'un module
		 * \details	Récupère un module si des paramètres existent (ACTION, MODULE, CONTEXT) et lui passe l'ensemble des paramètres ($_GET / $_POST). 
		 * \return	inclusion du module via getModule()
		 */
		function getDetailledModule ()
		{
			if(isset($_REQUEST['MODULE']) && isset($_REQUEST['ACTION']))
			{
				if(!$this->getModule(array(
					'MODULE' => $_REQUEST['MODULE'], 
					'ACTION' => $_REQUEST['ACTION'], 
					'CONTEXT' => isset($_REQUEST['CONTEXT']) ? $_REQUEST['CONTEXT'] : 'detail', 
					'PARAMS' => $_REQUEST
				)))
					die('ce module ne supporte pas cette action'); 
			}
		}
		
		// fonction qui vérifie si un module existe et qu'il est installé
		function doesExist ($moduleName)
		{
			return count($theModule = $this->selectData(array(
				'module' => array(
					'name', 'css', 'script'
				)
			), array(
				'where' => "`name` = '$moduleName'"
			))) > 0 ? $theModule[0] : false; 
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
			{
				// return false; 
				die("la requête demandé n'a pas aboutie pour : $sql"); 
			}
			
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
				for($i = 0; $i < count($champ); $i++)
					if($champ[$i] == '*')
						$sqlStart .= $champ[$i] . ', '; 
					else
						$sqlStart .= '`' . $champ[$i] . '`, '; 
			
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
			{
				// return false; 
				die('La requête d\'insertion semble vide (pas de paramètres pour <strong>insertData()</strong>)'); 
			}
		}
		
		function updateData ($queries)
		{
			foreach($queries AS $table => $champList)
			{
				$sql = "UPDATE `$table` SET "; 
				
				foreach($champList AS $champ => $value)
					if($champ != 'where')
						$sql .= "`$champ` = '$value', "; 
				
				
				$sql = substr($sql, 0, -2) . ' WHERE ' . $champList['where']; 
				
				if($this->mysqlQuery($sql))
					echo 'compte activé'; 
			}
		}
		
		// fonction de connexion à la base de donnée
		private function connectDb ()
		{
			$this->mysqli = new mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName); 
			$this->mysqli->set_charset('utf8'); 
		}
		
		function watchParams ()
		{
			if(isset($_REQUEST['MODULE']) && isset($_REQUEST['ACTION']))
				$this->getModule(array(
					'MODULE' => $_REQUEST['MODULE'], 
					'ACTION' => $_REQUEST['ACTION'], 
					'CONTEXT' => isset($_REQUEST['CONTEXT']) ? $_REQUEST['CONTEXT'] : null), 
					true
				); 
		}
		
		// fonction init de l'objet Brain
		function __construct ()
		{
			session_start(); 
			$this->connectDb(); 
			$this->startCapture(); 
			$this->watchParams(); 
		}
		
		// fonction qui nettoye les chaînes de caractères (html, caractères blancs, ...)
		function cleanString ($str, $pcre = null)
		{
			// TODO : cleaner les chaines 
			if($pcre)
				return !preg_match($pcre, $str) ? false : $str; 
			
			return $str; 
		}
		
		// mise en forme d'un texte monoligne
		function setSentence ($sentence)
		{
		}
		
		// mise en forme d'un texte multiligne
		function setText ($text)
		{
		}
		
		// fonction qui vérifie la validité d'un prénom
		function isValidFirstname ($firstname, $errorSpace = 'stored')
		{
			$firstname = $this->cleanString($firstname, '/^[A-Za-z]+$/'); 
			
			if(!$firstname || strlen($firstname) < 3)
			{
				$this->error[$errorSpace]['prénom'] = 'Votre prénom doit contenir 3 caractères minimum et ne peut contenir que des lettres'; 
				return false; 
			}
			
			return ucwords(strtolower($firstname)); 
		}
		
		// fonction qui vérifie la validité d'un nom
		function isValidLastname ($lastname, $errorSpace = 'stored')
		{
			$lastname = $this->cleanString($lastname, '/^[A-Za-z]+$/'); 
			
			if(!$lastname || strlen($lastname) < 3)
			{
				$this->error[$errorSpace]['nom'] = 'Votre nom doit contenir 3 caractères minimum et ne peut contenir que des lettres'; 
				return false; 
			}
			
			return ucwords(strtolower($lastname)); 
		}
		
		// fonction qui vérifie la validité d'une email
		function isValidEmail ($email, $errorSpace = 'stored')
		{
			$email = $this->cleanString($email); 
			
			if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$this->error[$errorSpace]['email'] = 'Votre email ne respect pas le format universel'; 
				return false; 
			}
			
			return strtolower($email); 
		}
		
		// fonction qui vérifie la validité d'un mot de passe
		function isValidPassword ($password, $errorSpace)
		{
			$password = $this->cleanString($password); 
			
			if(!$password || strlen($password) < 6)
			{
				$this->error[$errorSpace]['password'] = 'Votre mot de passe doit contenir 6 caractères minimum'; 
				return false; 
			}
			
			return md5($password); 
		}
	}
	
	class Email 
	{
		public static function sendEmail ($to, $subject, $template, $replyTo = null, $from = null)
		{
			if(!$replyTo)	$replyTo	= Brain::$email['replyTo']; 
			if(!$from)		$from		= Brain::$email['from']; 
			
			$content = Email::getTemplate($template); 
			
			if($content)
			{
				$headers	=  'From: ' . $from . " \r\n"; 
				$headers	.= 'Reply-To: ' . $replyTo . " \r\n"; 
				$headers	.= "MIME-Version: 1.0 \r\n"; 
				$headers	.= "Content-type: text/html; charset=utf-8 \r\n"; 
				
				if(!mail($to, $subject, $content, $headers))
					die('envoi du mail impossible'); 
				else
					echo 'envoi done !'; 
			}
			else
				die('une erreur est survenue lors de la récupération du template email (inexistant)'); 
		}
		
		public static function getTemplate ($params)
		{
			if(file_exists('email/' . $params['name'] . '.php'))
			{
				ob_start(); 
				include('email/' . $params['name'] . '.php'); 
				
				$content = ob_get_clean();
				
				unset($params['name']); 
				
				foreach($params AS $hash => $value)
					$content = preg_replace('/<#' . $hash . ' default=.*#>/', $value, $content); 
				
				return $content = preg_replace('/<#.*(default=(.*))#>/', '\2', $content); 
			}
			else
				return false; 
		}
	}
	
	$Brain = new Brain(); 
	// Email::sendEmail('gabin.aureche@live.fr', 'Inscription', 
		// array
		// (
			// 'name' => 'signup', 
			// 'tofirstname' => 'Gabin', 
			// 'tolastname' => 'Aureche', 
			// 'toemail' => 'gabin.aureche@live.fr'
		// )
	// ); 
?>

<!-- <script type="text/javascript" src="lib/Brain.js"></script> -->
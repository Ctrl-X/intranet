<?php
	// passer en mode debug
	define('_DEBUGMODE', true); 
	/*
		TODO : module pour dire quand quelqu'un sera à l'école (hors horaires de cours)
	*/
	
	// importation des fonctions génériques non associés à un objet
	require_once('lib.php'); 
	
	// la classe Brain est le moteur PHP principal
	class Brain
	{
		public static $email = array('replyTo' => 'postmaster@agora-supcrea.fr', 'from' => 'postmaster@agora-supcrea.fr'); 
		
		private $doNotInstall = array('Profile', 'UsefullLinks'); 
		
		// mysqli fonctionne pas chez ovh
		// public  $mysqli		= null; 
		
		public $mysql		= null; 
		
		private $dbHost 	= 'localhost'; 
		private $dbLogin 	= 'root'; 
		private $dbPass 	= ''; 
		private $dbName 	= 'agora'; 
		private $css		= array(); 
		private $js			= array(); 
		private $error		= array(); 
		
		function generateFileName ()
		{
			return time() . (rand(0, 9) * rand(0, 9)); 
		}
		
		public static function _text ($text, $end = null, $start = 0)
		{
			$text = Brain::cleanString($text); 
			
			if($end)
				$text = substr($text, $start, $end); 
			
			echo $text; 
		}
		
		public static function _echo ($str, $end = null, $start = 0)
		{
			$str = Brain::cleanString($str); 
			
			if($end)
				$str = substr($str, $start, $end); 
			
			echo $str; 
		}
		
		function cleanDirName ($str)
		{
			$invalidChar = array(
				"à", "â", "ç", "è", "é", "ê", "î", "ô", "ù", "û", 
				"Ä", "Æ", "Ç", "Ê", "Ì", "Í", "Î", "Ï", "Ð", "Ò", 
				"Ó", "Ô", "Õ", "Ö", "×", "Ø", "Ù", "Ú", "Û", "Ü",
				"Ý", "Þ", "ß", "á", "ã", "ä", "å", "æ", "ë", "ì",
				"í", "ï", "ð", "ñ", "ò", "ó", "õ", "ö", "÷", "ø", 
				"ù", "ú", "û", "ü", "ý", "þ", "ÿ", " ", "'"
			); 
			$replacement = array(
				"a", "a", "c", "e", "e", "e", "i", "o", "u", "u", 
				"a", "a", "c", "e", "i", "i", "i", "i", "d", "o", 
				"o", "o", "o", "o", "x", "o", "u", "u", "u", "u",
				"y", "", "", "a", "a", "a", "a", "ae", "e", "i",
				"i", "i", "o", "n", "o", "o", "o", "o", "", "o", 
				"u", "u", "u", "u", "y", "", "y", "_", ""
			); 
			
			return strtolower(str_replace($invalidChar, $replacement, $str)); 
		}
		
		/*
		 * $output		= tableau d'informations pour les fichiers de sorties
		 * $chName		= nom du champ file
		 * $dest		= répertoire de destination
		 * $filter		= possibilité de préciser un filtre photo
		 * $imagesOk	= tableau des formats de fichiers images acceptés
		 */
		function uploadFile ($output, $chName, $dest, $filter = null, $imagesOk = array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/pjpg', 'image/png', 'image/gif'))
		{
			if(!is_uploaded_file($tmpFile = $_FILES[$chName]['tmp_name'])) return false; 
			
			if(!in_array($fileType = $_FILES[$chName]["type"], $imagesOk)) return false; 
			
			switch($fileType)
			{
				case 'image/jpeg' || 'image/jpg' || 'image/pjpeg' || 'image/pjpg' : 
					$file = imagecreatefromjpeg($_FILES[$chName]['tmp_name']); break; 
					
				case 'image/png' :
					$file = imagecreatefrompng($_FILES[$chName]['tmp_name']); break; 
				
				case 'image/gif' : 
					$file = imagecreatefromgif($_FILES[$chName]['tmp_name']); break; 
				
				default : return false; break; 
			}
			
			$size = getimagesize($_FILES[$chName]['tmp_name']);
			$fileWidth = $size[0]; 
			$fileHeight = $size[1]; 
			
			$ext = explode('.', $_FILES[$chName]["name"]); 
			$ext = $ext[count($ext) - 1]; 
			
			$fileNameList = array(); 
			
			for($i = 0; $i < count($output); $i++)
			{
				$finalHeight = isset($output[$i]["height"]) ? $output[$i]["height"] : null; 
				$finalWidth = isset($output[$i]["width"]) ? $output[$i]["width"] : null; 
				
				$fileName = $this->generateFileName() . (isset($output[$i]['suffix']) ? $output[$i]['suffix'] : '') . '.' . $ext; 
				$fileNameList[] = $fileName; 
				
				if($fileWidth > $fileHeight)
				{
					$newHeight = $finalHeight; 
					$newWidth = $fileWidth * ($newHeight / $fileHeight); 
				}
				else
				{
					$newWidth = $fileWidth; 
					$newHeight = $fileHeight * ($newWidth / $fileWidth); 
				}
				
				$outputHeight = $finalHeight ? $finalHeight : $fileHeight * ($finalWidth / $fileWidth); 
				$outputWidth = $finalWidth ? $finalWidth : $fileWidth * ($finalHeight / $fileHeight); 
				
				$finalFile = imagecreatetruecolor($outputWidth, $outputHeight) or die("erreur lors de la création du calque d'accueil"); 
				imagecopyresampled($finalFile, $file, 0, 0, 0, 0, $newWidth, $newHeight, $fileWidth, $fileHeight); 
				
				if($output[$i]['filter'])
					switch($output[$i]['filter'])
					{
						case 'NEGATE'			: imagefilter($finalFile, IMG_FILTER_NEGATE); break; 
						case 'GRAYSCALE'		: imagefilter($finalFile, IMG_FILTER_GRAYSCALE); break; 
						case 'BRIGHTNESS'		: imagefilter($finalFile, IMG_FILTER_BRIGHTNESS, $output[$i]['args'][0]); break; 
						case 'CONTRAST'			: imagefilter($finalFile, IMG_FILTER_CONTRAST, $output[$i]['args'][0]); break; 
						case 'COLORIZE'			: imagefilter($finalFile, IMG_FILTER_COLORIZE, $output[$i]['args'][0], $output[$i]['args'][1], $output[$i]['args'][2]); break; 
						case 'EDGEDETECT'		: imagefilter($finalFile, IMG_FILTER_EDGEDETECT); break; 
						case 'EMBOSS'			: imagefilter($finalFile, IMG_FILTER_EMBOSS); break; 
						case 'GAUSSIAN_BLUR'	: imagefilter($finalFile, IMG_FILTER_GAUSSIAN_BLUR); break; 
						case 'SELECTIVE_BLUR'	: imagefilter($finalFile, IMG_FILTER_SELECTIVE_BLUR); break; 
						case 'MEAN_REMOVAL'		: imagefilter($finalFile, IMG_FILTER_MEAN_REMOVAL); break; 
						case 'SMOOTH'			: imagefilter($finalFile, IMG_FILTER_SMOOTH, $output[$i]['args'][0]); break; 
						case 'PIXELATE'			: imagefilter($finalFile, IMG_FILTER_PIXELATE, $output[$i]['args'][0], $output[$i]['args'][1]); break; 
					}
				
				
				switch($fileType)
				{
					case "image/jpeg" || "image/jpg" || "image/pjpeg" : 
						imagejpeg($finalFile , $dest . $fileName, 100) or die("création du fichier jpeg miniature impossible"); 
						break; 
					
					case "image/png" : 
						imagepng($finalFile , $dest . $fileName, 9) or die("création du fichier png miniature impossible"); 
						break;
					
					case "image/gif" : 
						imagegif($finalFile , $dest . $fileName) or die("création du fichier gif miniature impossible"); 
						break; 
				}
			}
			
			return $fileNameList; 
		}
		
		/**
		 * \brief	Lance l'exécution de scripts avant le chargement de la page
		 * \details	Vérifie si la page visité nécessite d'être connecté ou des paramètres ($_GET / $_POST)
		 * \param	$request Un tableau du type array('log' => array('request' => true, 'redirect' => 'index.php'), 'params' => array('request' => array('MODULE', 'ACTION'), 'redirect' => 'index.php'))
		 * \return	ne retourne rien, stop le chargement si le paramètre n'est pas passé
		 */
		function beforeLoad ($request = null)
		{
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
				$fullCss .= '<link type="text/css" rel="stylesheet" href="' . $this->css[$i] . '" />'; 
			
			
			$fullJs = ''; 
			for($i = 0; $i < count($this->js); $i++)
				$fullJs .= '<script type="text/javascript" src="' . $this->js[$i] . '"></script>'; 
			
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
			$installedModules = Brain::mysqlQuery('SELECT `name` FROM `module` ORDER BY `installDate` ASC'); 
			
			// on ouvre le dossier des modules
			$modulesFolder = opendir('modules'); 
			
			// on parcourt chacun des sous dossiers
			while($moduleName = readdir($modulesFolder))
			{
				// echo in_array_r($moduleName, $installedModules); 
				// on test si les modules ne sont pas déjà installés
				// si ce n'est pas le cas on vérifie qu'ils possèdent les fichiers élémentaires
				if($moduleName != '.' && $moduleName != '..' && is_dir('modules/' . $moduleName))
				{
					if(in_array_r($moduleName, $installedModules))
						echo "<strong>$moduleName</strong> : déjà installé<br/>"; 
					else
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
										'module'			=>
											array(
												'name'			=> $moduleName, 
												'script'		=> $moduleConfig['script'], 
												'css'			=> $moduleConfig['css'], 
												'installDate'	=> time(), 
												'cronTask'		=> $moduleConfig['cronTask'], 
												'topMenu'		=> $moduleConfig['topMenu']
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
						case 'install'		: $this->installModules(); break; 
						case 'signup'		: $this->signUp(); break; 
						case 'activate'		: $this->activeAccount(); break; 
						case 'lostPass'		: $this->newPass(isset($_REQUEST['email']) ? $_REQUEST['email'] : null); break; 
						case 'signin'		: $this->signIn(); break; 
						case 'disconnect'	: $this->disconnect(); break; 
						case 'sendEmail'	: Email::sendEmail($_REQUEST['to'], $_REQUEST['subject'], $_REQUEST['template'], isset($_REQUEST['replyTo']) ? $_REQUEST['replyTo'] : null, isset($_REQUEST['from']) ? $_REQUEST['from'] : null); break; 
						
						// actions en ajax
						case 'selectData' : 
							echo json_encode($this->selectData($_REQUEST['data'], $_REQUEST['clause'])); break; 
						case 'test' : 
							echo '<div style="width:500px; height:500px; background-color:red;"></div>'; break; 
						
						default : return false; break; 
					}
				else if($preloaded || $_COMMAND['ACTION'] == 'install' || $theModule = $this->doesExist($_COMMAND['MODULE']))
				{
					if(!$preloaded && $_COMMAND['ACTION'] != 'install')
					{
						$this->addCss($theModule['name'], $theModule['css']); 
						$this->addJs($theModule['name'], $theModule['script']); 
					}
					
					return include('modules/' . $_COMMAND['MODULE'] . '/' . $_COMMAND['MODULE'] . '.php'); 
				}
			}
		}
		
		public static function log ($text)
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
		
		function newPass ($userEmail = null, $sendEmail = true)
		{
			if(!$userEmail)
				return false; 
			
			$newPass = $this->generatePass(); 
			
			// TODO : le template mail newpass
			if($sendEmail)
			{
				if(Email::sendEmail($userEmail, 'nouveau mot de passe', 
					array
					(
						'name'		=> 'newpass', 
						'newpass'	=> $newPass
					)
				))
					return Brain::mysqlQuery("UPDATE `utilisateur` SET `pass` = '" . md5($newPass) . "' WHERE `email` = '$userEmail'"); 
				else
					return false; 
			}
			else
				return Brain::mysqlQuery("UPDATE `utilisateur` SET `pass` = '$newPass' WHERE `email` = '$userEmail'"); 
		}
		
		function generatePass ($length = 8)
		{
			$char = array(
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
				'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 
				'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 
				'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 
				'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 
				'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 
				'W', 'X', 'Y', 'Z', '0', '1', '2', '3', 
				'4', '5', '6', '7', '8', '9'
			); 
			
			$newPass = ''; 
			
			for($i = 0; $i < $length; $i++)
				$newPass .= $char[rand(0, count($char) - 1)]; 
			
			return $newPass; 
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
			header('Location: login.php'); 
		}
		
		function signIn ()
		{
			if(isset($_REQUEST['email']) && isset($_REQUEST['pass']))
			{
				if(count($user = Brain::mysqlQuery('SELECT `id_utilisateur`, `prenom`, `utilisateur`.`nom`, `email`, `pass`, `statut`, `rang`, `date_inscription`, `derniere_connexion`, `hash`, `classe`.`nom` AS `classe` FROM `utilisateur` LEFT JOIN `classe` ON `utilisateur`.`id_classe` = `classe`.`id_classe` WHERE `email` = \'' . $_REQUEST['email'] . '\' AND `pass` = \'' . md5($_REQUEST['pass']) . '\'')) > 0)
				{
					if($user[0]['rang'] != 'inactif' && $user[0]['rang'] != 'désactivé')
					{
						$_SESSION = $user[0]; 
						$this->updateData(array(
							'utilisateur' => array(
								'derniere_connexion' => time(), 
								'where' => '`email` = \'' . $user[0]['email'] . '\'' 
							)
						)); 
						
						header('Location: index.php'); 
					}
					else
					{
						echo 'compte inactif'; 
					}
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
					'prenom'	=> 'isValidFirstname', 
					'nom'		=> 'isValidLastname', 
					'email'		=> 'isValidEmail', 
					'pass'		=> 'isValidPassword'
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
						$portrait = $this->uploadFile(
						array
						(
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_normal', 
								'filter'	=> null, 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_negate', 
								'filter'	=> 'NEGATE', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_gray', 
								'filter'	=> 'GRAYSCALE', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_brithness', 
								'filter'	=> 'BRIGTHNESS', 
								'args'		=> array(20), 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_contrast', 
								'filter'	=> 'CONTRAST', 
								'args'		=> array(20), 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_colorize', 
								'filter'	=> 'COLORIZE', 
								'args'		=> array(0, 255, 120), 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_edgedetect', 
								'filter'	=> 'EDGEDETECT', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_emboss', 
								'filter'	=> 'EMBOSS', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_gaussian_blur', 
								'filter'	=> 'GAUSSIAN_BLUR', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_selective_blur', 
								'filter'	=> 'SELECTIVE_BLUR', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_mean_removal', 
								'filter'	=> 'MEAN_REMOVAL', 
								'args'		=> null, 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_smooth', 
								'filter'	=> 'SMOOTH', 
								'args'		=> array(6), 
								'crop'		=> false
							),
							array
							(
								'height'	=> 400, 
								'width'		=> 400, 
								'suffix'	=> '_pixelate', 
								'filter'	=> 'PIXELATE', 
								'args'		=> array(15, true), 
								'crop'		=> false
							)
						), 'portrait', 'images/portrait/'); 
						
						$portrait = $portrait ? 'images/portrait/' . $portrait[0] : ''; 
						
						if($this->insertData(array(
							'utilisateur' => array(
								'prenom'				=> $prenom, 
								'nom'					=> $nom, 
								'email'					=> $email, 
								'portrait'				=> $portrait, 
								'pass'					=> $pass, 
								'statut'				=> $_REQUEST['statut'], 
								'id_classe'				=> $_REQUEST['statut'] != 'etudiant' ? $this->getClasseId('cadre') : $_REQUEST['classe'], 
								'rang'					=> 'inactif', 
								'date_inscription'		=> time(), 
								'derniere_connexion'	=> 0, 
								'hash' 					=> $hash = uniqid('', true) 
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
							
							echo "l'inscription s'est bien passé"; 
						}
					}
				}
			}
		}
		
		function getClasseId ($name)
		{
			$id = Brain::mysqlQuery("SELECT `id_classe` FROM `classe` WHERE `classe`.`nom` = '$name' OR `classe`.`code` = '$name'", false); 
			
			return $id[0]['id_classe']; 
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
		
		function signal ($table, $idch, $idrow)
		{
			$signaledRow = Brain::mysqlQuery("SELECT * FROM `$table` WHERE `$idch` = $idrow", false); 
			
			$message = "Contenu signalé : \r\n\r\n"; 
			foreach($signaledRow[0] AS $key => $val)
				$message .= "$key : $val \r\n"; 
			
			if(!Brain::mysqlQuery("INSERT INTO `signaled` (`table`, `idrow`) VALUES ('$table', $idrow)"))
				$message .= "\r\n\r\n IMPORTANT : l'insertion dans la base a rencontré des problèmes et il est possible que le contenu soit toujours visible"; 
			
			$admin = Brain::mysqlQuery('SELECT `email` FROM `utilisateur` WHERE `rang` = \'admin\'', false); 
			for($i = 0; $i < count($admin); $i++)
				Email::sendEmail($admin[$i]['email'], 'un nouveau contenu a été signalé', $message); 
		}
		
		public static function mysqlQuery ($sql, $secure = true)
		{
			// on ajoute une condition where pour ne pas récupérer un contenu signalé dans les select
			// ce qui suit modifie complètement la requête sql pour y ajouter un morceau (lier la table signaled et ne retourner que les résultats non signalés
			if($select = stristr($sql, 'SELECT'))
			{
				// pour récupérer même les résultats signalés on peut passer secure à false
				// la modification de la requête sql peut être un peu lourde (interrogation du schéma de la table, ...)
				if($secure)
				{
					$table = explode(' ', substr($sql, stripos($sql, 'FROM'))); 
					$table = str_replace('`', '', $table[1]); 
					
					$configTable = Brain::mysqlQuery("SHOW INDEXES FROM `$table`"); 
					$configTable = mysql_fetch_assoc($configTable); 
					
					$idTable = $configTable['Column_name']; 
					
					if(($where = stripos($sql, 'WHERE')) !== false)
						$sql = substr($sql, 0, $where - 1) . " LEFT JOIN `signaled` ON `signaled`.`table` = '$table' AND `signaled`.`idrow` = `$table`.`$idTable` WHERE `signaled`.`id_signaled` is null AND " . substr($sql, $where + 6); 
					else
					{
						if((($pos = stripos($sql, 'GROUP')) !== false) || (($pos = stripos($sql, 'HAVING')) !== false) || (($pos = stripos($sql, 'ORDER')) !== false) || (($pos = stripos($sql, 'LIMIT')) !== false) || (($pos = stripos($sql, 'PROCEDURE')) !== false))
							$sql = substr($sql, 0, $pos - 1) . " LEFT JOIN `signaled` ON `signaled`.`table` = '$table' AND `signaled`.`idrow` = `$table`.`$idTable` WHERE `signaled`.`id_signaled` is null " . substr($sql, $pos); 
						else
							$sql .= " LEFT JOIN `signaled` ON `signaled`.`table` = '$table' AND `signaled`.`idrow` = `$table`.`$idTable` WHERE `signaled`.`id_signaled` is null"; 
					}
				}
			}
			else
				Brain::log($sql); 
			
			$query = mysql_query($sql) or die(mysql_error()); 
			
			if($select)
			{
				$output = array(); 
				while($row = mysql_fetch_assoc($query))
					$output[] = $row; 
				
				return $output; 
			}
			
			return $query; 
			/*
			// mysqli ne fonction pas chez ovh
			$query = strstr($sql, ';') ? $this->mysqli->multi_query($sql) : $this->mysqli->query($sql); 
			
			if($query)
				return is_object($query) ? $query->fetch_all(MYSQLI_ASSOC) : $query; 
			else
			{
				echo '<strong>' . $this->mysqli->error . '</strong><br /><br />'; 
				die($sql); 
			}
			*/
		}
		
		function getClasse ($all = false)
		{
			return Brain::mysqlQuery('SELECT * FROM `classe` WHERE `active` = 1 ' . ($all ? '' : ' AND `selectable` = 1') . ' ORDER BY `id_classe` ASC'); 
		}
		
		function getClasseForm($name = 'classe', $type = 'select', $selectedDefault = true, $all = false)
		{
			if($selectedDefault && (!isset($_SESSION) || empty($_SESSION)))
				$selectedDefault = false; 
			
			$classes = Brain::mysqlQuery('SELECT `id_classe`, `nom`, `code` FROM `classe` WHERE `active` = 1 ' . ($all ? '' : ' AND `selectable` = 1') . ' ORDER BY `id_classe` ASC'); 
			
			if($type == 'select')
			{
				$select = "<select name=\"$name\">"; 
				
				for($i = 0; $i < count($classes); $i++)
					if($selectedDefault && $_SESSION['classe'] == $classes[$i]['nom'])
						$select .= '<option value="' . $classes[$i]['id_classe'] . '" selected="selected">' . $classes[$i]['nom'] . '</option>'; 
					else
						$select .= '<option value="' . $classes[$i]['id_classe'] . '">' . $classes[$i]['nom'] . '</option>'; 
				
				$select .= "</select>"; 
				
				echo $select; 
			}
			else if($type == 'checkbox')
			{
				if($name == 'classe')
					$name = ''; 
				
				$checkboxes = ''; 
				for($i = 0; $i < count($classes); $i++)
				{
					$checkboxes .= '<label for="' . $name . $classes[$i]['nom'] . '">' . $classes[$i]['nom'] . ' :</label>'; 
					if($selectedDefault && $_SESSION['classe'] == $classes[$i]['nom'])
						$checkboxes .= '<input type="checkbox" name="' . $name . $classes[$i]['code'] . '" value="' . $classes[$i]['id_classe'] . '"  checked="checked" />'; 
					else
						$checkboxes .= '<input type="checkbox" name="' . $name . $classes[$i]['code'] . '" value="' . $classes[$i]['id_classe'] . '" />'; 
				}
					
				echo $checkboxes; 
			}
			else if($type == 'radio')
			{
			}
			else if($type == 'link')
			{
			}
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
					
					Brain::mysqlQuery(substr($sql, 0, -2) . ')'); 
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
			
			return Brain::mysqlQuery($sql); 
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
					Brain::mysqlQuery($sql); 
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
				
				Brain::mysqlQuery($sql); 
			}
		}
		
		// fonction de connexion à la base de donnée
		private function connectDb ()
		{
			$this->mysql = mysql_connect($this->dbHost, $this->dbLogin, $this->dbPass); 
			mysql_select_db($this->dbName); 
			mysql_query("SET NAMES 'utf8'"); 
			/*
			// mysqli ne fonction pas chez ovh
			$this->mysqli = new mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName); 
			$this->mysqli->set_charset('utf8'); 
			*/
		}
		
		// TOFIX : utile pour les commandes ajax
		function watchParams ()
		{
			if(isset($_REQUEST['MODULE']) && !empty($_REQUEST['MODULE']) && isset($_REQUEST['ACTION']) && !empty($_REQUEST['ACTION']))
				$this->getModule(array(
					'MODULE' => $_REQUEST['MODULE'], 
					'ACTION' => $_REQUEST['ACTION'], 
					'CONTEXT' => isset($_REQUEST['CONTEXT']) ? $_REQUEST['CONTEXT'] : null), 
					false
				); 
		}
		
		// fonction init de l'objet Brain
		function __construct ()
		{
			session_start(); 
			$this->connectDb(); 
			$this->startCapture(); 
			$this->watchParams(); 
			$this->cleanPosted(); 
		}
		
		// fonction qui nettoye les chaînes de caractères (html, caractères blancs, ...)
		public static function cleanString ($str, $pcre = null)
		{
			$str = strip_tags(trim($str)); 
			
			if($pcre)
				return !preg_match($pcre, $str) ? false : $str; 
			
			return $str; 
		}
		
		private function cleanPosted ()
		{
			if(isset($_REQUEST) && !empty($_REQUEST))
				foreach($_REQUEST AS $key => $val)
					$_REQUEST[$key] = Brain::cleanString($val); 
		}
		
		// fonction qui vérifie la validité d'un prénom
		function isValidFirstname ($firstname, $errorSpace = 'stored')
		{
			$firstname = Brain::cleanString($firstname, '/^[A-Za-z]+$/'); 
			
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
			$lastname = Brain::cleanString($lastname, '/^[A-Za-z]+$/'); 
			
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
			$email = Brain::cleanString($email); 
			
			if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$this->error[$errorSpace]['email'] = 'Votre email ne respect pas le format universel'; 
				return false; 
			}
			
			return strtolower($email); 
		}
		
		// fonction qui vérifie la validité d'un mot de passe
		function isValidPassword ($password, $errorSpace = 'stored')
		{
			$password = Brain::cleanString($password); 
			
			if(!$password || strlen($password) < 6)
			{
				$this->error[$errorSpace]['password'] = 'Votre mot de passe doit contenir 6 caractères minimum'; 
				return false; 
			}
			
			return md5($password); 
		}
		
		function isValidFile ($file, $errorSpace = 'stored')
		{
			if(empty($file) || !is_uploaded_file($file['tmp_name']))
			{
				$this->error[$errorSpace]['portrait'] = 'Vous devez choisir une image de profil'; 
				return false; 
			}
			
			return true; 
		}
	}
	
	class Email
	{
		public static function sendEmail ($to, $subject, $template, $replyTo = null, $from = null)
		{
			if(!$replyTo)	$replyTo	= Brain::$email['replyTo']; 
			if(!$from)		$from		= Brain::$email['from']; 
			
			$headers =  'From: ' . $from . " \r\n"; 
			$headers .= 'Reply-To: ' . $replyTo . " \r\n"; 
			$headers .= "MIME-Version: 1.0 \r\n"; 
			
			if(is_array($template))
			{
				$content = Email::getTemplate($template); 
				$headers .= "Content-type: text/html; charset=utf-8 \r\n"; 
			}
			else
			{
				$content = $template; 
				$headers .= "Content-type: text/plain; charset=utf-8 \r\n"; 
			}
			
			if($content)
			{
				if(!mail($to, '=?utf-8?b?' . base64_encode($subject) . '==?=', $content, $headers))
					die('envoi du mail impossible'); 
				else
					echo 'envoi done !'; 
			}
			else
				die('probleme avec le contenu de l\'email (template peut être inexistant)'); 
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
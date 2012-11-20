<?php
	if(isset($_COMMAND))
	{
		switch($_COMMAND['ACTION'])
		{
			// début de l'installation du module
			case 'install' : 
				return array(
					'script'	=> 'fichier.js',	// liste des fichiers javascripts, séparés par une virgule (exemple : fichier1.js,fichier2.js)
					'css'		=> 'fichier.css',	// liste des fichiers css, séparés par une virgule (exemple : fichier1.css, fichier2.css)
					'cronTask'	=> 0,				// si le module a des tâches automatiques et régulières à effectuer : 1 sinon 0
					'topMenu'	=> 0,				// le lien vers ce module doit faire partie du menu principal : 1 sinon 0
					
					'table'		=> array(
						// liste des tables à créer pour le module
						'entreprises' => 
							array
							(
								'id_entreprise'		=> 'int NOT NULL AUTO_INCREMENT', 
								'id_utilisateur'	=> 'int NOT NULL',
								'nom'				=> 'varchar(120)', 
								'adresse'			=> "varchar(150) default ''",
								'tel'				=> "varchar(20) default''",
								'mail'				=> 'varchar(120)',
								'url'				=> "varchar(250) default ''",
								'PRIMARY KEY'		=> '(id_entreprise)'
							),
						
						'stages' =>
							array
							(
								'id_stage'			=> 'int NOT NULL AUTO_INCREMENT', 
								'id_entreprise'		=> 'int NOT NULL', 
								'id_utilisateur'	=> 'int NOT NULL', 
								'id_classe'			=> 'int NOT NULL', 
								'titre'				=> 'varchar(180)', 
								'annonce'			=> 'text', 
								'PRIMARY KEY'		=> '(id_stage)'
							)
					)
				); break; 
			// fin de l'installation du module
			
			// les fichiers php (les modèles)
			case 'model' :
				switch($_COMMAND['CONTEXT'])
				{
					case 'tile'		: include('tile.php'); break;	// affichage en 'tuile', sur la page d'accueil
					case 'detail'	: include('detail.php'); break; // affichage en détail, sur la page de détail
					/*
					 * Ici viennent les autres actions qui dépendent de votre module
					 * Soit par exemple le formulaire d'ajout / modification ce qui donnerait :
					 * case 'ajoutmodification' : include('ajoutmodification.php'); break; 
					 * l'exemple veut dire qu'il va afficher le fichier ajoutmodification.php, le formulaire en html/php doit donc y être
					 */
				} break; 
		}
	}
	
	return false; 
?>
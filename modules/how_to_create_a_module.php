<?php
	/*
		Ici un exemple minimal qui permet d'installer un nouveau module. 
		L'installation nécessite tout simplement une position (c'est là où le module apparaîtra. 
		Le nom du dossier et du fichier .php doivent être le même, c'est celui du module. 
		
		Les différentes actions possibles dépendent des modules mais les principales sont :
			install : lance l'installation du module
			model : retourne le html dépendant du contexte
			data : retourne simplement un/des tableaux de données
				** model & data ont besoin d'un paramètre supplémentaire, le contexte
				context : donne le contexte (page de détail, affichage principal, ...)
				** des paramètres peuvent être passés 
				params : paramètres liés à la requête par exemple : (?id_user=1&order=name)
		
		Etant à l'intérieur de l'objet Brain l'appel des fonctions se fait de cette façon : $this->fonctionDuBrain(); 
	*/
	
	// on vérifie qu'une commande est passé ($_COMMAND)
	// elle ressemble à ça : array( "MODULE" => "monModule", "ACTION" => "model", "CONTEXT" => "tile" );
	if(isset($_COMMAND))
	{
		// selon l'action passé dans $_COMMAND le retour est différent
		switch($_COMMAND["ACTION"])
		{
			// ici le module est masqué et effectura probablement des "tâches de fond"
			case "install" : 
				$moduleConfig = array(
					"position" => "hidden", 
					"style" => "css/unFichier.css, css/unDeuxiemeFichier.css", 
					"script" => "js/unFichier.js, js/unDeuxiemeFichier.js"
				); break; 
		}
	}
?>
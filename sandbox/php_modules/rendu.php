<?php
	function _exist ($champList, $method = 'both')
	{
		switch(strtolower($method))
		{
			case 'post'	: $global = $_POST; break; 
			case 'get'	: $global = $_GET; break; 
			default		: $global = $_REQUEST; break; 
		}
		
		for($i = 0; $i < count($champList); $i++)
		{
			$key = $champList[$i]; 
			if(!isset($global[$key]) || empty($global[$key]))
				return false; 
		}
		
		return true; 
	}
	// -------------------------
	
	// script pour insérer un nouveau rendu
	if(isset($_POST['envoi']))
	{
		$champList = array(
			'matiere', 'titre', 'description', 'date_rendu'
		); 
		
		if(!_exist($champList))
		{
			echo 'les champs classe, matiere, titre, description et date de rendu sont obligatoires'; 
		}
		else
		{
			extract($_POST); 
			$error = array(); 
			
			if(strlen($matiere) < 4)
				$error['matiere'] = '4 caractères minimum'; 
			
			if(strlen($titre) < 4)
				$error['titre'] = '4 caractères minimum'; 
			
			if(strlen($description) < 8)
				$error['description'] = '8 caractères minimum'; 
			
			if(empty($error))
			{
				$id_utilisateur = 1; 
				$id_classe = 5; 
				
				$jour = substr($date_rendu, 0, 2); 
				$mois = substr($date_rendu, 3, 2); 
				$annee = substr($date_rendu, -4); 
				$date_rendu = mktime(0, 0, 0, $mois, $jour, $annee); 
				
				// _mysql_query("INSERT INTO `rendu` (`id_classe`, `id_utilisateur`, `matiere`, `titre`, `description`, `date_rendu`) VALUES ($id_classe, $id_utilisateur, '$matiere', '$titre', '$description', $date_rendu)"); 
			}
			else
			{
				print_r($error); 
			}
		}
	}
	// -------------------------------------------------------------------------
?>

<form method="post" action="">
	<input type="text" name="matiere" value="matiere" />
	<input type="text" name="titre" value="titre" />
	<textarea name="description">description</textarea>
	<input type="text" name="date_rendu" value="18/02/2012" />
	
	<input type="submit" name="envoi" value="envoyer" />
</form>
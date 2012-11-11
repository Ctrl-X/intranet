<?php
	if(isset($_COMMAND))
	{
		switch($_COMMAND["ACTION"])
		{
			case 'install' : 
				return array(
					'position' => 'hidden', 
					'script' => 'SignIU.js', 
					'style' => 'SignIU.css', 
					'table' => array(
						'utilisateur' => array(
							'id_utilisateur' => 'integer', 
							'prenom' => 'char(50)',
							'nom' => 'char(50)', 
							'email' => 'char(50)',
							'classe' => 'char(50)',
							'PRIMARY KEY' => '(id_utilisateur)'
						)
					)
				); break; 
				
		}
	}
?>
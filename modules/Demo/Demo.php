<?php
	if(isset($_COMMAND))
	{
		switch($_COMMAND["ACTION"])
		{
			case 'install' : 
				return array(
					'position' => 'tile', 
					'script' => 'Demo.js', 
					'style' => 'Demo.css', 
					'table' => array(
						'demo' => array(
							'id_demo' => 'int NOT NULL AUTO_INCREMENT', 
							'intitule' => 'varchar(50)', 
							'description' => 'varchar(50)',
							'PRIMARY KEY' => '(id_demo)'
						)
					)
				); break; 
			
			case 'model' :
				switch($_COMMAND['CONTEXT'])
				{
					case 'tile' : include('tile.php'); break; 
				} break; 
		}
	}
?>
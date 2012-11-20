<?php
	if(isset($_COMMAND))
	{
		switch($_COMMAND['ACTION'])
		{
			case 'install' : 
				return array(
					'script' => 'Demo.js', 
					'css' => 'Demo.css', 
					'table' => array(
						'demo' => array(
							'id_demo' => 'int NOT NULL AUTO_INCREMENT', 
							'intitule' => 'varchar(50)', 
							'description' => 'varchar(50)',
							'PRIMARY KEY' => '(id_demo)'
						)
					)
				); break; 
			
			case 'add' : include('add.php'); break; 
			
			case 'model' :
				switch($_COMMAND['CONTEXT'])
				{
					case 'tile' : include('tile.php'); break; 
				} break; 
			
			case 'do' : 
			/* ------------------------------------------------- */
				// print_r($_COMMAND['PARAMS']); 
				
				if($this->isValidPassword($_COMMAND['PARAMS']['pass']))
					echo 'ok'; 
				else
					echo 'not ok'; 
				
				break; 
			/* ------------------------------------------------- */
			
			default : return false; break; 
		}
	}
	
	return false; 
?>
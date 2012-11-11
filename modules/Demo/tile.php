<?php
	$demoResult = $this->selectData(array('demo' => array('intitule', 'description'))); 
	
	for($i = 0; $i < count($demoResult); $i++)
	{
		$theDemo = $demoResult[$i]; 
?>

	<div style="width:150px; height:150px; background-color:blue; float:left; margin:0 5px 0 0;">
		<h3><?php echo $theDemo['intitule']; ?></h3>
	</div>

<?php
	}
?>
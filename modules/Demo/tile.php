<?php
	$demoResult = $this->selectData(array('demo' => array('intitule', 'description'))); 
	
	for($i = 0; $i < count($demoResult); $i++)
	{
		$theDemo = $demoResult[$i]; 
?>

	<div style="width:130px; height:130px; background-color:#dadada; float:left; padding:5px; margin:5px 0 5px 5px;">
		<h3><?php echo $theDemo['intitule']; ?></h3>
	</div>

<?php
	}
?>
<?php
	if(isset($_POST["intitule"]))
	{
		// est ce que le password est valide ? 
		if($this->isValidPassword($_POST["intitule"]))
		// si oui :
		{
			echo "ok"; 
		}
		// si non :
		else
		{
		}
	}
?>

<form method="POST" action="?MODULE=Demo&ACTION=add">
	<input type="text" name="intitule" value="Albert" />
	<textarea name="description">ma description</textarea>
	
	<input type="submit" value="envoyer" />
</form>
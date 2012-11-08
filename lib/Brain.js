// Brain est le moteur javaScript principal
var Brain = 
{
	// récupère une chaîne propre
	getCleanString : function()
	{
	}, 
	
	// vérifie la validité d'un nom / prénom
	isValidName : function()
	{
	}, 
	
	// vérifie la validité d'une adresse email
	isValidEmail : function()
	{
	}, 
	
	// vérifie la validité d'un mot de passe
	isValidPassword : function()
	{
	}, 
	
	// formate comme un prénom
	setFirstname : function()
	{
	}, 
	
	// formate un nom
	setLastname : function()
	{
	}, 
	
	// formate un password
	setPassword : function()
	{
	}, 
	
	// formate une adresse email
	setEmail : function()
	{
	}, 
	
	// formate un texte monoligne
	setSentence : function()
	{
	}, 
	
	// formate un texte multiligne
	setText : function()
	{
	}, 
	
	// fonction à appeller pour envoyer un email en javaScript
	sendEmail : function(to, message, replyTo, from)
	{
		/*
			to : destinataires du mail (tableau)
			message : le contenu du message
			replyTo : adresse mail de la personne à qui répondre
			from : adresse de l'expéditeur
		*/
	}, 
	
	// sous objet qui va gérer les accès à la base de donnée
	dataBase :
	{
		mysqlQuery : function(sql)
		{
		}, 
		
		// insérer des données dans la base
		insertData : function(queries) // { user : { nom : "", prenom : "", email : "" }}
		{
		}, 
		
		// supprimer des données dans la base
		deleteData : function(queries)
		{
		}, 
		
		// mettre à jour des données dans la base
		updateData : function(queries)
		{
		}
	}
}; 
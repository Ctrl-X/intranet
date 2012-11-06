// Brain est le moteur javaScript principal
var Brain = 
{
	// vérifie la validité d'un élément
	check : function(type)
	{
		switch(type)
		{
			case "firstName" : 
				return function()
				{
				}; break; 
			
			case "lastName" : 
				return function()
				{
				}; break; 
			
			case "email" : 
				return function()
				{
				}; break; 
			
			case "password" : 
				return function()
				{
				}; break; 
			
			default : return false; break; 
		}
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
	}
}; 

// console.log(Brain.check.firstName("")); 
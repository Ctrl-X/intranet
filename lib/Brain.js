var Brain = 
{
	init : function()
	{
		$('#maincolumn').isotope({
			itemSelector : '.tile',
			layoutMode : 'masonry'
		});
	}, 
	
	getModule : function(_COMMAND, done)
	{
		$.ajax(
		{
			type: "POST",
			url: "Brain.php",
			data: { "MODULE" : _COMMAND["MODULE"], "ACTION" : _COMMAND["ACTION"], "preloaded" : true }
		
		}).done(function(result)
		{
			done(result); 
		});
	}, 
	
	selectData : function(data, clause, done)
	{
		$.ajax(
		{
			type: "POST",
			url: "Brain.php",
			data: { "MODULE" : "Brain", "ACTION" : "selectData", "data" : data, "clause" : clause }
		
		}).done(function(result)
		{
			result = $.parseJSON(result); 
			done(result); 
		});
	}, 
	
	// TODO		: confirmer une action
	// TOFIX	: ne marche que sur les liens
	confirm : function(selector, message)
	{
		$(selector).click(function(event){
			if(confirm(message))
				location.href = $(this).attr("href"); 
			
			event.stopPropagation(); 
			event.preventDefault(); 
			return false; 
		}); 
	}, 
	
	lostPass : function(email)
	{
		$.ajax(
		{
			type: "POST",
			url: "Brain.php",
			data: { "MODULE" : "Brain", "ACTION" : "lostPass", "email" : email }
		
		}).done(function(result)
		{
			// result = $.parseJSON(result); 
			done(result); 
		});
	}
}; 

$(document).ready(function(){
	// Brain.init(); 
}); 
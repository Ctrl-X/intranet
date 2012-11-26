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
	}
}; 

$(document).ready(function(){
	Brain.init(); 
}); 
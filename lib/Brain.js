var Brain = 
{
	init : function()
	{
		$('#maincolumn').isotope({
			itemSelector : '.tile',
			layoutMode : 'masonry'
		});
	}
}; 

$(document).ready(function(){
	Brain.init(); 
}); 
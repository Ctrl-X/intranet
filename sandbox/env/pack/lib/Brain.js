var Brain = 
{
	init : function()
	{
		$('#container').isotope({
			itemSelector : '.item',
			layoutMode : 'masonry'
		});
	}
}; 

$(document).ready(function(){
	Brain.init(); 
}); 
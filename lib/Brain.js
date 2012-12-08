var Brain = 
{
	init : function(param)
	{
		var parentSelector = "#maincolumn", 
			selector = ".tile"; 
		
		// on vérifie les paramètres passés à l'init
		if(typeof param != "undefined")
		{
			if(typeof param["isotope"] != "undefined")
			{
				if(typeof param["isotope"]["parentSelector"] != "undefined")
					parentSelector = param["isotope"]["parentSelector"]; 
				
				if(typeof param["isotope"]["selector"] != "undefined")
					selector = param["isotope"]["selector"]; 
			}
		}
		
		// init isotope
		$(parentSelector).isotope({ 
			itemSelector : selector, 
			layoutMode : "masonry", 
			resizesContainer : true 
		}); 
		
		$(parentSelector).isotope({
			masonry : {
				columnWidth : 225
			}
		});
		
		// init support placeholder
		$('input[placeholder], textarea[placeholder]').placeholder();
		
		// init des formulaires perso
		this.niceForm(); 
		
		// init de la locale défaut des datepicker
		$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
		
		$(".error .head .close").click(function(){
			$(this).parent().parent(".error").fadeOut(); 
		}); 
	}, 
	
	setGlobal : function(name, obj)
	{
		if(typeof global[name] == "undefined")
			global[name] = {}; 
		
		for(var key in obj)
			global[name][key] = obj[key]; 
	}, 
	
	check : function(list)
	{
		var idElem, f; 
		
		for(var key in list)
		{
			idElem = key; 
			
			Brain.setGlobal( idElem, { check : list[key] } ); 
			
			$("#"+idElem).keyup(function(){
				if(global[$(this).attr("id")]["check"](this.value))
					$(this).removeClass("unvalid").addClass("valid"); 
				else
					$(this).removeClass("valid").addClass("unvalid"); 
			}); 
		}
	}, 
	
	niceForm : function()
	{
		var elements = document.getElementsByClassName("niceform"); 
		
		for(var i = 0, e; i < elements.length; i++)
		{
			e = elements[i]; 
			
			switch(e.tagName)
			{
				case "UL" :
					$(e).append("<span class=\"nfInputs\"></span>"); 
					
					for(var j = 0, ipt = $(e).children(".nfInputs"), radio, label, children = $(e).children("li"); j < children.length; j++)
					{
						label = $(children[j]).children("label").text(); 
						radio = $(children[j]).children("input"); 
						if(j == 0)
							$(ipt).append("<input type=\"radio\" name=\""+$(radio).attr("name")+"\" value=\""+$(radio).attr("value")+"\" checked=\"checked\" />"); 
						else
							$(ipt).append("<input type=\"radio\" name=\""+$(radio).attr("name")+"\" value=\""+$(radio).attr("value")+"\" />"); 
						$(children[j]).html(label); 
					}
					
					$(e).children("li:eq(0)").addClass("checked"); 
					$(e).children("li:gt(0)").addClass("unchecked"); 
					
					$(e).children("li").click(function(){
						$(this).siblings("li").removeClass("checked").addClass("unchecked"); 
						$(this).removeClass("unchecked").addClass("checked"); 
						$(this).parent().find(".nfInputs input").removeAttr("checked"); 
						$(this).parent().find(".nfInputs input:eq("+$(this).index()+")").attr("checked", "checked"); 
					}); 
			}
		}
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
},
global = {}; 
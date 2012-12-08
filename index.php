<?php
	include('_boot/Brain.php'); 
	$Brain->beforeLoad(array(
		'log'		=> array('request' => true),	// nécessite que l'utilisateur soit connecté ou pas
		'params'	=> array('request' => false)	// liste des paramètres nécessaires à l'affichage de la page
	)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include('_boot/head.php'); ?>
		<title>holder</title>
		<script type="text/javascript">
			$(document).ready(function(){
				Brain.init(); 
			}); 
		</script>
		<script type="text/javascript">
			/*
			Brain.selectData(
			{
				"utilisateur" : ["nom", "prenom"]
			}, null, function(result){
				for(i = 0; i < result.length; i++)
					for(key in result[i])
						console.log(result[i][key]); 
			}); 
			
			Brain.getModule({
				"MODULE" : "Brain", 
				"ACTION" : "test"
			}, function(result){
				// $("body").append(result); 
			}); 
			
			$(document).ready(function(){
				Brain.confirm("#confirm", "u sure ??"); 
			}); 
			*/
			
			$(document).ready(function(){
				$(".colorpickerpink").hover(function(){
					$("body").removeClass("saumon").removeClass("blue").addClass("pink"); 
				}); 
				
				$(".colorpickersaumon").hover(function(){
					$("body").removeClass("pink").removeClass("blue").addClass("saumon"); 
				}); 
				
				$(".colorpickerblue").hover(function(){
					$("body").removeClass("pink").removeClass("saumon").addClass("blue"); 
				}); 
			}); 
		</script>
	</head>
	<body class="<?php if(isset($_SESSION['color']) && !empty($_SESSION['color'])) echo $_SESSION['color']; else echo 'pink'; ?>">
		<div class="colorpickerpink"></div>
		<div class="colorpickersaumon"></div>
		<div class="colorpickerblue"></div>
		<?php
			// _echo("<div style=\"width:250px;height:250px;background-color:red;\"></div>"); 
			// _text("<div style=\"width:250px;height:250px;background-color:red;\"></div>"); 
			// $Brain->generatePass(); 
			// $Brain->signal('utilisateur', 'id_utilisateur', 1); 
			// $Brain->getClasseForm('select', 'lenom', true); 
			// $Brain->getClasseSelect(); 
			// $Brain->printModule(); 
			
			// print_r($_SESSION); 
		?>
		<!-- <a id="confirm" href="http://google.com">here we go</a> -->
		<div id="container">
			<div id="leftcolumn">
				<div id="bandeau">
					
				</div>
				
				<div class="tile _1x1 profil">
					<img src="images/portrait/def.gif" alt="" title="" width="120" height="120" />
					
					<div class="infos">
						<?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' - ' . $_SESSION['classe']; ?>
						<a href="index.php?MODULE=Brain&ACTION=disconnect&CONTEXT=data" class="btn">déconnexion</a>
					</div>
					
					<ul class="links">
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
						<li><div style="width:25px; height:25px; background-color:#dadada;"></div></li>
					</ul>
				</div>
				
				<ul class="menu">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<div class="clear"></div>
				</ul>
			</div>
			
			<div id="maincolumn">
				<?php $Brain->printModule(); ?>
				<!--
				<div id="matuile" class="tile _1x3">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _2x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x2">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				
				<div class="tile _1x1">
					<h3>Mon module</h3>
					<div class="content">
						contenu de mon module
					</div>
				</div>
				-->
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
	</body>
</html>
<?php
	$Brain->endCapture(); 
?>
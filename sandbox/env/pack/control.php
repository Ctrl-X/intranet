<div id="controlpannel">
	<div>
		<ul>
			<li>
				<fieldset style="padding:8px;">
					<legend>changer l'utilisateur connecté</legend>
					<form method="post" action="">
						<input type="hidden" name="cp-reset" value="1" />
						<input type="submit" value="changer d'utilisateur (aléatoire)" />
					</form>
				</fieldset>
			</li>
			
			<li>
				<fieldset style="padding:8px;">
					<legend>installer les modules</legend>
					
					<form method="get" action="">
						<input type="hidden" name="MODULE" value="Brain" />
						<input type="hidden" name="ACTION" value="install" />
						<input type="submit" value="installer les modules" />
					</form>
				</fieldset>
			</li>
			<div style="clear:both;"></div>
		</ul>
		<br />
		
		<fieldset style="padding:8px;">
			<legend>modifier la requête module</legend>
			
			<form method="get" action="">
				<ul>
					<li style="width:150px;"><label for="MODULE">MODULE :</label></li>
					<li style="width:150px;"><input type="text" name="MODULE" value="<?php if(isset($_REQUEST['MODULE'])) echo $_REQUEST['MODULE']; ?>" /></li>
					
					<li style="width:150px;"><label for="ACTION">ACTION :</label></li>
					<li style="width:150px;"><input type="text" name="ACTION" value="<?php if(isset($_REQUEST['ACTION'])) echo $_REQUEST['ACTION']; ?>" /></li>
					
					<li style="width:150px;"><label for="CONTEXT">CONTEXT :</label></li>
					<li style="width:150px;"><input type="text" name="CONTEXT" value="<?php if(isset($_REQUEST['CONTEXT'])) echo $_REQUEST['CONTEXT']; ?>" /></li>
					
					<li style="width:150px;"><input type="submit" value="modifier la requête module" /></li>
					<div style="clear:both;"></div>
				</ul>
			</form>
		</fieldset>
		<br />
		
		<fieldset style="padding:8px;">
			<legend>modifier les informations sur l'utilisateur connecté</legend>
			
			<form method="post" action="">
			<ul>
				<li><label for="cp-id_utilisateur">id_utilisateur :</label></li>
				<li><input type="text" name="cp-id_utilisateur" value="<?php echo $_SESSION['id_utilisateur']; ?>" /></li>
				
				<li><label for="cp-prenom">prenom :</label></li>
				<li><input type="text" name="cp-prenom" value="<?php echo $_SESSION['prenom']; ?>" /></li>
				
				<li><label for="cp-nom">nom :</label></li>
				<li><input type="text" name="cp-nom" value="<?php echo $_SESSION['nom']; ?>" /></li>
				
				<li><label for="cp-email">email :</label></li>
				<li><input type="text" name="cp-email" value="<?php echo $_SESSION['email']; ?>" /></li>
				
				<li><label for="cp-pass">pass :</label></li>
				<li><input type="text" name="cp-pass" value="<?php echo $_SESSION['pass']; ?>" /></li>
				
				<li><label for="cp-statut">statut :</label></li>
				<li>
					<select name="cp-statut">
						<option value="étudiant" <?php if($_SESSION['statut'] == 'étudiant') echo 'selected="selected"'; ?>>étudiant</option>
						<option value="intervenant" <?php if($_SESSION['statut'] == 'intervenant') echo 'selected="selected"'; ?>>intervenant</option>
						<option value="administration" <?php if($_SESSION['statut'] == 'administration') echo 'selected="selected"'; ?>>administration</option>
					</select>
				</li>
				
				<li><label for="cp-classe">classe :</label></li>
				<li>
					<select name="cp-classe">
						<option value="préparatoire" <?php if($_SESSION['classe'] == 'préparatoire') echo 'selected="selected"'; ?>>préparatoire</option>
						<option value="DG1" <?php if($_SESSION['classe'] == 'DG1') echo 'selected="selected"'; ?>>DG1</option>
						<option value="DG2" <?php if($_SESSION['classe'] == 'DG2') echo 'selected="selected"'; ?>>DG2</option>
						<option value="DG3" <?php if($_SESSION['classe'] == 'DG3') echo 'selected="selected"'; ?>>DG3</option>
						<option value="DG4" <?php if($_SESSION['classe'] == 'DG4') echo 'selected="selected"'; ?>>DG4</option>
						<option value="WD" <?php if($_SESSION['classe'] == 'WD') echo 'selected="selected"'; ?>>WD</option>
						<option value="WD Alt" <?php if($_SESSION['classe'] == 'WD Alt') echo 'selected="selected"'; ?>>WD Alt</option>
						<option value="Infog" <?php if($_SESSION['classe'] == 'Infog') echo 'selected="selected"'; ?>>Infog</option>
						<option value="Infog Alt" <?php if($_SESSION['classe'] == 'Infog Alt') echo 'selected="selected"'; ?>>Infog Alt</option>
						<option value="3D" <?php if($_SESSION['classe'] == '3D') echo 'selected="selected"'; ?>>3D</option>
					</select>
				</li>
				
				<li><label for="cp-rang">rang :</label></li>
				<li>
					<select name="cp-rang">
						<option value="inactif" <?php if($_SESSION['rang'] == 'inactif') echo 'selected="selected"'; ?>>inactif</option>
						<option value="normal" <?php if($_SESSION['rang'] == 'normal') echo 'selected="selected"'; ?>>normal</option>
						<option value="modérateur" <?php if($_SESSION['rang'] == 'modérateur') echo 'selected="selected"'; ?>>modérateur</option>
						<option value="admin" <?php if($_SESSION['rang'] == 'admin') echo 'selected="selected"'; ?>>admin</option>
					</select>
				</li>
				
				<li><label for="cp-date_inscription">date_inscription :</label></li>
				<li><input type="text" name="cp-date_inscription" value="<?php echo $_SESSION['date_inscription']; ?>" /></li>
				
				<li><label for="cp-derniere_connexion">derniere_connexion :</label></li>
				<li><input type="text" name="cp-derniere_connexion" value="<?php echo $_SESSION['derniere_connexion']; ?>" /></li>
				
				<li><label for="cp-hash">hash :</label></li>
				<li><input type="text" name="cp-hash" value="<?php echo $_SESSION['hash']; ?>" /></li>
				
				<li><input type="submit" value="modifier l'utilisateur connecté" /></li>
			</ul>
			</form>
		</fieldset>
	</div>
</div>
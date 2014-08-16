<div class="col-6-12 eg0">
<form method="post">
	<h3>Opret profil</h3>
	<fieldset>
		<label>Navn</label>
		<input type="tekst" name="name" placeholder="Fulde navn" value="<? echo $name; ?>"/>
		<label>E-mail</label>
		<input type="tekst" name="email" placeholder="Din e-mail-adresse" value="<? echo $email; ?>"/>
		<label class="eg0">Adgangskode</label>
		<input type="password" name="password" placeholder="Ny adgangskode" class="eg0" value="<? echo $password; ?>" class="eg0"/>
	</fieldset>
	<div class="col-8-12 eg0 form_info"><? echo $info; ?></div><div class="col-4-12"><button type="submit" name="register">Gem info</button></div>
	<? if (isset($focus))echo '<script> $("input[name=\''.$focus.'\']").focus(); </script>'; ?>
</form>
</div>
<div class="col-6-12">
	
</div>
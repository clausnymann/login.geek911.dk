<form method="post" id="loginForm">
	<h3>Login</h3>
	<fieldset>
		<label>E-mail</label>
		<input type="tekst" name="email" placeholder="Din e-mail-adresse" value="<? echo $email; ?>" id="email"/>
		<label class="eg0">Adgangskode</label>
		<input type="password" name="password" placeholder="Adgangskode" value="<? echo $password; ?>" id="password" class="eg0"/>
	</fieldset>
	<div class="col-8-12 eg0 form_info"><? echo $info; ?></div><div class="col-4-12"><button type="submit" name="login">Login</button></div>
	<? if (isset($focus))echo '<script> $("input[name=\''.$focus.'\']").focus(); </script>'; ?>
	<br />
	<a href="/auth/register">Opret profil</a>
	<a href="/auth/forget-password" id="forget-password">Glemt password?</a>
</form>
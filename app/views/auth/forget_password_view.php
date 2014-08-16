<form method="post" id="forgetPasswordForm">
	<h3>Glemt password</h3>
	<div class="info">Udfyld formen så sender vi et nyt password til den e-mail du er registreret med.</div>
	<fieldset>
		<label class="eg0">E-mail</label>
		<input type="tekst" name="email" placeholder="Din e-mail-adresse" class="eg0" id="email"/>
	</fieldset>
	<div class="col-7-12 eg0 form_info"><? echo $info; ?></div><div class="col-5-12"><button type="submit" name="send_password">Send password</button></div>
	<br />
	<a href="/auth/register">Opret profil</a>
	<a href="/auth/login" id="loginBtn">Login</a>
	<? if (isset($focus))echo '<script> $("input[name=\''.$focus.'\']").focus(); </script>'; ?>
</form>
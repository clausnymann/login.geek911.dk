<div class="user_nav">
	<? if($auth_access != true) {?>
		<a href="/auth/login" class="loginBtn">Login</a> | <a href="/auth/register">Opret profil</a>
	<? } else { ?>
		<a href="/auth/account/<? echo $user_id; ?>"><? echo $username; ?></a> | <a href="/auth/logout">Logout</a>
	<? } ?>
</div>
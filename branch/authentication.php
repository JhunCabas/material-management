<?php
include './resources/init.php';
if(isSet($_POST['type']))
{
	if($_POST['type'] == "logout")
		fAuthorization::destroyUserInfo();
	else if($_POST['type'] == "login")
	{
		try{
			$user = new User($_POST['username']);
		} catch (fException $e) {
			fURL::redirect(URL_ROOT."authentication.php");
		}
		if(sha1($_POST['password']) == $user->getPassword())
		{
			fAuthorization::setUserAuthLevel($user->getLevel());
			fAuthorization::setUserToken($_POST['username']);
			fURL::redirect(fAuthorization::getRequestedUrl(true,URL_ROOT."inventory.php"));
		}
		else {
			fURL::redirect(URL_ROOT."authentication.php");
		}
	}
}else if(isSet($_GET['type']) == "logout")
{
	fAuthorization::destroyUserInfo();
}
$tmpl->place('header');
$tmpl->place('menu');
?>
<div class="span-24 last">
	<span id="statusbar"></span>
<form id="loginForm" action="authentication.php" method="post" accept-charset="utf-8">
	<div id="loginBox">
		<label for="username">Username: </label>
		<input type="text" id="username" name="username"></input><br />
		<label for="password">Password: </label>
		<input type="password" id="password" name="password"></input>
		<input type="hidden" name="type" value="login"/>
	</div>
	<p><input type="submit" id="submitBTN" value="Continue &rarr;"></p>
</form>
</div>
<?php $tmpl->place('footer'); ?>
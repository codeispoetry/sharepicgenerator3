
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo _('Sharepicgenerator');?></h1>
			<form method="post" action="index.php/frontend/create">
				<input type="text" name="username" id="username" placeholder="<?php  echo _('username');?>" value="mail@tom-rose.de">
				<input type="passwordKILL" name="password" id="password" placeholder="<?php  echo _('password');?>" value="geheim">
				<input type="submit" name="submit" value="<?php  echo _('login');?>">
			</form>
		</div>
		
	</div>	
</main>








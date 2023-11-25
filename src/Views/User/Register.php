
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo _('Register');?></h1>
			<form method="post" action="/index.php/frontend/register">
				<input type="email" name="register_mail" placeholder="<?php  echo _('email');?>">
				<input type="submit" name="submit" value="<?php  echo _('register');?>">
			</form>
		</div>
		
	</div>	
</main>








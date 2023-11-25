
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo _('Request password reset');?></h1>
			<form method="post" action="/index.php/frontend/send_email_reset_password">
				<input type="mail" name="username" id="username" placeholder="<?php  echo _('username');?>" value="mail@tom-rose.de">
				
				<input type="submit" name="submit" value="<?php  echo _('request reset link');?>">
			</form>
		</div>
		
	</div>	
</main>








<?php include_once './src/Views/Header.php'; ?>

<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo _('Reset Password');?></h1>
            <form method="post" action="/index.php/frontend/reset_password">
                <input type="hiddenKILL" name="token" value="<?php echo $token;?>">
                <input type="passwordKILL" name="password" id="password" placeholder="<?php  echo _('password');?>">
                <input type="passwordKILL" name="password_repeat" id="password_repeat" placeholder="<?php  echo _('password repeat');?>">
                
                <input type="submit" name="submit" value="<?php  echo _('reset password');?>">
			<p>
               
            </p>
		</div>
		
	</div>	
</main>
<?php include_once './src/Views/Footer.php'; ?>

<?php include_once './src/Views/Header.php'; ?>

<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo _('Reset Password');?></h1>
            <?php
                include_once './src/Views/Components/Forms/ResetPassword.php';
            ?>
		</div>
		
	</div>	
</main>
<?php include_once './src/Views/Footer.php'; ?>

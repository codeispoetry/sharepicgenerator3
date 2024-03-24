<?php include_once './src/Views/Header.php'; ?>
<main class="main">
	<div id="title">
		<h1><?php  echo _('Sharepicgenerator');?></h1>
		<p id="description">
			<?php
				echo _('Create sharepics for your social media channels in the layout of the German Green Party.');
			?>
		</p>
	</div>
	<div id="loginboxes">
		<div id="loginbox">
			<?php
				echo '<h3>' . _('Login') . '</h3>';
			?>

			<a href="index.php?c=frontend&m=create" class="button">
				<img src="assets/icons/login.svg" style="margin-right: 10px">
				<?php echo _('Login as guest');?>
			</a>

			<?php /*
			<a href="index.php?c=frontend&m=create" class="button">
				<img src="assets/icons/login.svg" style="margin-right: 10px">
				<?php  echo _('login with Green Net');?>
			</a>

			<small style="text-align: right; margin-top: 20px">
				<a href="index.php?self=true">
					<?php  echo _('Login as guest');?>
				</a>
			</small>

			*/ ?>

		</div>
	
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

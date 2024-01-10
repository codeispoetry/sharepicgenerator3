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
			<a href="index.php?c=frontend&m=create" class="flat">
				<?php  echo _('login');?>
			</a>

		</div>
	
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

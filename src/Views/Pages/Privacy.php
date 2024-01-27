<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="padding: 40px">
	<div id="title">
		<h1><?php  echo _('Privacy');?></h1>
		<div id="description">
			<?php
				include_once './src/Views/Pages/Privacy.html';
			?>
		</div>
	</div>
</main>

<style>
	h5{
		font-weight: bold;
		font-size: 1.4em;
		margin: 1.9em 0 0 0;
	}
</style>

<?php include_once './src/Views/Footer.php'; ?>

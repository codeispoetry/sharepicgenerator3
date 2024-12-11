<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="padding: 40px">

	<a href="/index.php">
		<?php echo _('back'); ?>
	</a>

	<div id="">
		<h1><?php  echo _('Privacy');?></h1>
		<div id="description" style="padding-bottom: 4em">
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
	body.home .main{
		background: none;
	}
</style>


<script>
	document.getElementById('menu_sharepics').remove();
</script>

<?php include_once './src/Views/Footer.php'; ?>

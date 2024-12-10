<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="padding: 20px; width: auto;background-image:none;font-size:1em;">

	<a href="/index.php">
		<?php echo _('back'); ?>
	</a>

	<div id="title" style="width: 80%">
		<h1><?php  echo _('Imprint');?></h1>
		
		<h4>Dies ist ein Angebot von:</h4>
		<p id="description">
        	Thomas Rose<br>
			Frauenkirchstraße 20<br>
			72800 Eningen unter Achalm<br>
			<br>
			E-Mail: <a href="MAILTO:mail@tom-rose.de?subject=Sharepicgenerator">mail@tom-rose.de</a>
	</p>
	</div>
</main>

<script>
	document.getElementById('menu_sharepics').remove();
</script>

<?php include_once './src/Views/Footer.php'; ?>

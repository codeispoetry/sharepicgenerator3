<script src="/assets/script.js?r=<?php echo rand(); ?>" defer></script>
<script>
	<?php
		$fontFiles = glob('assets/fonts/*.woff2');
		$fontFiles = array_map(function ($filename) {
			return pathinfo($filename, PATHINFO_FILENAME);
		}, $fontFiles);
		printf("var fonts = [ '%s' ]", implode("', '", $fontFiles));
	?>
</script>

<main class="main">
	<div class="row">
		<div id="workbench" class="workbench">
			<div id="adder">
				<div class="add_sign"><img src="/assets/icons/add_circle.svg"></div>
				<ul class="add_elements">
					<li data-item="eyecatcher">Störer</li>
				</ul>
			</div>
			<div id="canvas">
				<div id="sharepic">
					
				</div>
			</div>
			<div id="inlinecockpit">
				<button class="create flat"><img src="/assets/icons/download.svg"> Create</button>
			</div>
		</div>
		<div id="cockpit" class="cockpit">
			<?php
			foreach (glob("src/Views/Cockpit/*.php") as $filename) {
				include $filename;
			}
			?>
		</div>
		<?php
			include 'src/Views/Pixabay.php';
		?>
	</div>

	<div class="row">
		<img src="" id="output">
	</div>
</main>

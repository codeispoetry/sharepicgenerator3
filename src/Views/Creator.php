<script src="/assets/script.js" defer></script>


<main class="main">
	<div class="row">
		<div id="workbench" class="workbench">
			<div id="canvas">
				<div id="sharepic">
					
				</div>
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

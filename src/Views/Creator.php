<script src="assets/script.js" defer></script>

<main class="main">
	<div class="row">
		<div class="workbench">
			<div id="canvas" contenteditable="true">
				<div id="sharepic">
					<h1 id="text1" class="draggable selectable">Void</h1>
				</div>
			</div>
		</div>
		<div class="cockpit">
			<?php
				include 'src/Views/Cockpit/Sharepic.php';
			?>
			<input type="range" min="0" max="100" value="50" class="slider" id="fontsize" oninput="font_size(event)">
		</div>
	</div>
	
	<div class="row">
		<img src="" id="output" style="border: 1px solid blue">
	</div>
</main>







<script src="/assets/script.js?r=<?php echo rand(); ?>" defer></script>


<main class="main">
	<div class="row">
		<div id="workbench" class="workbench">
			<div id="adder">
				<div class="add_sign"><img src="/assets/icons/add.png"></div>
				<ul class="add_elements">
					<li data-item="eyecatcher">Störer</li>
				</ul>
			</div>
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

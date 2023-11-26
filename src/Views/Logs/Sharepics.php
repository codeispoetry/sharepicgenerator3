<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php echo _('Latest sharepics'); ?></h1>
			<p>
                <?php
					$files = glob('./tmp/*.{jpg,png,gif}', GLOB_BRACE);

					usort($files, function($a, $b) {
						return ( filemtime($a) < filemtime($b) ) ? 1 : -1;
					});

                    foreach($files as $file) {
						echo '<img src="/' . $file . '" style="margin: 10px;">';
					}
                ?>
            </p>
		</div>
		
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

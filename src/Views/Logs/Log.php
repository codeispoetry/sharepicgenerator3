<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php echo _('Usage Log'); ?></h1>
			<p>
                <?php
					$lines = file( './logs/usage.log' );
					$lines = array_reverse( $lines );
                    foreach( $lines as $line ) {	
						printf( '%s<br>', $line );
					}
                ?>
            </p>
		</div>
		
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

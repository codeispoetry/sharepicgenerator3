<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="background-image: none; height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php echo _('Usage Log'); ?></h1>
			<p>
                <?php
					$lines = file( '../logfiles/usage.log' );
					$lines = array_reverse( $lines );
                    foreach( $lines as $line ) {
						list($time, $user, $entry) = explode( "\t", $line );

						if( str_starts_with( $entry, 'template' ) ) {
							continue;
						}

						printf( '%s %s %s<br>', 
							$time, 
							substr( $user, 0, 12 ), 
							$entry
						);
					}
                ?>
            </p>
		</div>
		
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

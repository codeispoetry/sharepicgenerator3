<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="background-image: none; height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php echo _('Usage Overview'); ?></h1>
			<ul>
                <li>
					<?php
						$now = new DateTime();
						$beginning = new DateTime( cmd( 'head -n1 ../logfiles/usage.log | cut -f1' ) );
						$since = $beginning->diff( $now )->format( '%a days, %h hours, %i minutes' );
						printf( _('Logging since: %s'), $since );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of users: %s'), cmd( 'cut -f2 ../logfiles/usage.log | sort | uniq | wc -l' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics: %s'), cmd( 'grep -c \'creates sharepic\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Clicks on image after imagedb search: %s'), cmd( 'grep -c \'clicks on image after search for\' ../logfiles/usage.log' ) );
					?>
				</li>
            </ul>
		</div>
		
	</div>	
</main>
<?php

function cmd( $cmd ){
	$output = shell_exec( $cmd );
	if( is_numeric( $output ) ) {
		return number_format( $output, 0, ',', '.' );
	}

	return $output;
}
?>
<?php include_once './src/Views/Footer.php'; ?>

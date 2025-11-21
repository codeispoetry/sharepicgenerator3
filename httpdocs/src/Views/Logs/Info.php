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
						$since = $beginning->diff( $now )->format( _( '%a days, %h hours, %i minutes' ) );
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
				<li>
					<?php
						printf( _('Number of tmp-files: %s'), cmd( 'ls -l ../tmp/ | wc -l' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics for de: %s'), cmd( 'grep -c \'creates sharepic from template templates/de/start.html\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics for hessen: %s'), cmd( 'grep -c \'creates sharepic from template templates/hessen/start.html\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics for bavaria: %s'), cmd( 'grep -c \'creates sharepic from template templates/by/start.html\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics for NRW: %s'), cmd( 'grep -c \'creates sharepic from template templates/nrw/start.html\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of sharepics for mv: %s'), cmd( 'grep -c \'creates sharepic from template templates/mv/start.html\' ../logfiles/usage.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Number of Rembg usages: %s'), cmd( 'grep -c \'Rembg API success\' ../logfiles/access.log' ) );
					?>
				</li>
				<li>
					<?php
						printf( _('Chrome version: %s'), cmd( 'google-chrome --version' ) );
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

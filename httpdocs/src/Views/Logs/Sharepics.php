<?php include_once './src/Views/Header.php'; ?>
<?php

use Sharepicgenerator\Controllers\Helper;

Helper::clean_up_dir( '../tmp/*.png', 3 * 24 * 60 );

$files = glob( '../tmp/*.png' );

usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$files = array_map(
	function( $filename ) {
		return substr( $filename, 3, );
	},
	$files
);
?>
<main class="main" style="background-image: none; height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php echo _('Latest sharepics'); ?></h1>
			<p>
                <?php
                    foreach($files as $file) {
						echo '<img src="index.php?c=proxy&p=' . $file . '" style="margin: 10px;">';
					}
                ?>
            </p>
		</div>
		
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

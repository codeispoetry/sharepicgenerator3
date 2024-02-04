<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
    <link href="node_modules/quill/dist/quill.bubble.css" rel="stylesheet">
    <script src="node_modules/quill/dist/quill.js"></script>
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        #canvas{
            zoom: 1 !important;
        }
    </style>
</head>
<body>
    
<header>
    <?php
		$templates = $this->config->get('Templates');
		include 'src/Views/Menu.php'; 
	?>

    <div style="display: flex; align-items: center">
        <?php if( $this->user->is_logged_in() ): ?>
            <a href="index.php?c=frontend&m=logout" class="link">
                <img src="assets/icons/logout.svg" style="height: 1em;">
			</a>
        <?php endif; ?>
    </div>
</header>

<script src="assets/script.js?r=<?php echo rand(); ?>" defer></script>
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
			<div style="display: flex; justify-content: center;">
				<div id="canvas">
					<div id="sharepic">
						
					</div>
				</div>
			</div>
			<div id="inlinecockpit">
				<button class="create flat" data-click="api.create"><img src="assets/icons/download.svg"> <?php  echo _('Download');?></button>
			</div>
		</div>
		<div id="cockpit" class="cockpit">
			<div id="componentscockpit">
				<?php
				foreach ( glob( "src/Views/Cockpit/*.php" ) as $filename ) {
					include $filename;
				}
				?>
			</div>
			<div id="sitecockpit">
				<button data-showtab="download">
					<img src="assets/icons/download.svg" title="<?php echo _('Download'); ?>">
				</button>
				<button data-pseudoselect="sharepic" class="active">
					<img src="assets/icons/image.svg" title="<?php echo _('Image'); ?>">
				</button>
				<button data-pseudoselect="freetext">
					<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
				</button>
				<button data-pseudoselect="eyecatcher">
					<img src="assets/icons/eye.svg" title="<?php echo _('Eyecatcher'); ?>">
				</button>
				<button data-pseudoselect="copyright">
					<img src="assets/icons/attribution.svg" title="<?php echo _('Copyright'); ?>">
				</button>
				<button data-showtab="dalle">
					<img src="assets/icons/robot.svg" title="<?php echo _('Dall-E'); ?>">
				</button>
			</div>
		</div>
		<?php
			include 'src/Views/Pixabay.php';
		?>
	</div>
</main>

<dialog id="waiting">
  <p>
	<?php
		echo _('Please wait ...');
	?>
  </p>
</dialog>

<script>
    const config = {
        'pixabay': {
            'apikey': '<?php echo $this->config->get( 'Pixabay', 'apikey' ); ?>'
        },
		'url': '<?php echo $this->config->get( 'Main', 'url' ); ?>',
		'starttemplate': '<?php echo $this->config->get( 'Main', 'starttemplate' ); ?>',
	};
	
	const lang = {
		'Are you sure?': '<?php echo _( 'Are you sure?') ?>',
		'All changes lost': '<?php echo _( 'Please save your sharepic. All changes will be lost.') ?>',
		'Enter prompt for image': '<?php echo _( 'Please enter a text describing your desired image.') ?>',

    };
</script>

</body>
</html>
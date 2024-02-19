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
		$published = $this->get_published();
		include 'src/Views/Menu.php'; 
	?>

    <div style="display: flex; align-items: center">
        <?php if( $this->user->is_logged_in() ): ?>
			<?php
				echo _('Logged in as');
				echo ' ' . $this->user->get_username();
			?>
            <a href="index.php?c=frontend&m=logout" class="link">
                <img src="assets/icons/logout.svg" style="height: 1em; margin-left: 1em;">
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
		//printf("var fonts = [ '%s' ]", implode("', '", $fontFiles));

		echo "var fonts = ['Baloo2', 'Roboto-Light', 'Calibri', 'SaunaPro']";
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
				<button class="create flat" onClick="api.create()"><img src="assets/icons/download.svg"> <?php  echo _('Download');?></button>
			</div>
		</div>
		<div id="cockpit" class="cockpit">
			<div id="components">
				<?php
				foreach ( glob( "src/Views/Cockpit/*.php" ) as $filename ) {
					include $filename;
				}
				?>
			</div>
			<div id="componentbuttons">
				<button id="tab_btn_download" onClick="ui.showTab('download')">
					<img src="assets/icons/download.svg" title="<?php echo _('Download'); ?>">
					<div class="description">
						<?php echo _('Download'); ?>
					</div>
				</button>
				<button id="tab_btn_search"  onClick="ui.showTab('search')">
					<img src="assets/icons/search_image.svg" title="<?php echo _('Search or create image'); ?>">
					<div class="description">
						<?php echo _('Search or create image'); ?>
					</div>
				</button>
				<button id="tab_btn_background" onClick="ui.showTab('background')" class="active">
					<img src="assets/icons/wallpaper.svg" title="<?php echo _('Image'); ?>">
					<div class="description">
						<?php echo _('Image'); ?>
					</div>
				</button>
				<button id="tab_btn_freetext" onClick="ui.showTab('freetext')">
					<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
					<div class="description">
						<?php echo _('Text'); ?>
					</div>
				</button>
				<button id="tab_btn_eyecatcher" onClick="ui.showTab('eyecatcher')">
					<img src="assets/icons/eye.svg" title="<?php echo _('Eyecatcher'); ?>">
					<div class="description">
						<?php echo _('Eyecatcher'); ?>
					</div>
				</button>
				<button id="tab_btn_addpicture"  onClick="ui.showTab('addpicture')">
					<img src="assets/icons/add_image.svg" title="<?php echo _('Front picture'); ?>">
					<div class="description">
						<?php echo _('Front picture'); ?>
					</div>
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
		'starttemplate': '<?php echo $starttemplate; ?>',
	};
	
	const lang = {
		'Are you sure?': '<?php echo _( 'Are you sure?') ?>',
		'All changes lost': '<?php echo _( 'Please save your sharepic. All changes will be lost.') ?>',
		'Enter prompt for image': '<?php echo _( 'Please enter a text describing your desired image.') ?>'
    };
</script>

</body>
</html>
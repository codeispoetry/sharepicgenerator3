<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
	<?php if( $body_classes === 'greens') { ?>
		<meta name="apple-itunes-app" content="app-id=6738736776">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
	<?php } else { ?>
		<link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
	<?php } ?>
    <link rel="stylesheet" href="assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
    <link rel="stylesheet" href="<?php echo Sharepicgenerator\Controllers\Fonts::get_css_file(); ?>">

    <style>
        #canvas{
            zoom: 1 !important;
        }
    </style>
</head>
<body class="<?php echo $body_classes;?>">
    
<header>
    <?php
		$show_my_sharepics = true;
		$menu = 'src/Views/Menu/' . ucFirst( $this->env->config->get( 'Main', 'tenant' ) ) . '.php';
		if( ! file_exists( $menu ) ) {
			echo "Could not find menu file: $menu";
			exit( 1 );	
		}
		include $menu;
		?>
</header>

<script src="assets/script.js?r=<?php echo rand(); ?>" defer></script>

<main class="main">
	<div class="row">
		<div id="workbench" class="workbench">
			<div style="display: flex; justify-content: center;">
				<div>
					<div style="display: flex;justify-content: space-between;align-items: center;">
						<button onClick="undo.undo()" id="undo" class="no-button">
							<img src="assets/icons/undo.svg" title="<?php echo _('Undo'); ?>">	
						</button>
					</div>
					<div id="canvas" translate="no">
						<div id="sharepic">
							
						</div>
					</div>
					<div id="image-credits">
						
					</div>
				</div>
			</div>
			<div class="workbench-below" <?php if( 'robert' === $this->env->user->get_username() ) echo 'style="display:none;"';?>>
				<button class="create" style="border-top-right-radius: 0; border-bottom-right-radius:0;" onClick="ui.downloadButton()">
					<img src="assets/icons/download.svg"> <?php  echo _('Download');?>
				</button>
				<select class="button-dropdown" id="format">
					<option value="png" title="<?php echo _('best quality');?>">png</option>
					<option value="jpg" title="<?php echo _('smaller file size');?>">jpg</option>
					<option value="spg" title="<?php echo _('editable file');?>">spg</option>
				</select>

				<div class="message"></div>
			</div>
		</div>
		<div id="cockpit" class="cockpit">
			<div id="tabs">
				<?php
				foreach ( glob( "src/Views/Cockpit/*.php" ) as $filename ) {
					include $filename;
				}
				?>
			</div>
			<div id="tabsbuttons">
				<button id="tab_btn_canvas" onClick="ui.showTab('canvas')">
					<div>
						<img src="assets/icons/canvas.svg" title="<?php echo _('Canvas'); ?>">
						<div class="description">
							<?php echo _('Canvas'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_background" onClick="ui.showImageTab();" class="active">
					<div>
						<img src="assets/icons/picture.svg" title="<?php echo _('Background image'); ?>">
						<div class="description">
							<?php echo _('Background image'); ?>
						</div>
					</div>
				</button>
				
				<button id="tab_btn_eyecatcher" onClick="ui.showTab('eyecatcher')">
					<div>
						<img src="assets/icons/eye.svg" title="<?php echo _('Eyecatcher'); ?>">
						<div class="description">
							<?php echo _('Eyecatcher'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_addpicture"  onClick="ui.showSearchImageTab('addpic');">
					<div>
						<img src="assets/icons/grafics.svg" title="<?php echo _('Front picture'); ?>">
						<div class="description">
							<?php echo _('Graphics and foreground pictures'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_freetext" onClick="ui.showTab('freetext')">
					<div>
						<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
						<div class="description">
							<?php echo _('Text'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_greenclassictext" onClick="ui.showTab('greenclassictext')">
					<div>
						<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
						<div class="description">
							<?php echo _('Main Text'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_greentext" onClick="ui.showTab('greentext')">
					<div>
						<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
						<div class="description">
							<?php echo _('Add Text'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_vorort" onClick="ui.showTab('vorort')">
					<div>
						<img src="assets/icons/resize.svg" title="<?php echo _('Vor Ort'); ?>">
						<div class="description">
							<?php echo _('Vor Ort'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_greenaddtext" onClick="ui.showTab('greenaddtext')">
					<div>
						<img src="assets/icons/asterisk.svg" title="<?php echo _('Additional text'); ?>">
						<div class="description">
							<?php echo _('Additional text'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_europe" onClick="ui.showTab('europe')">
					<div>
						<img src="assets/icons/europe.svg" title="<?php echo _('European Election'); ?>">
						<div class="description">
							<?php echo _('European Election'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_logomv" onClick="ui.showTab('logomv')">
					<div>
						<img src="assets/icons/sunflower.svg" title="<?php echo _('Logo'); ?>">
						<div class="description">
							<?php echo _('Logo'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_elements" onClick="ui.showTab('elements')">
					<div>
						<img src="assets/icons/elements.svg" title="<?php echo _('Elements'); ?>">
						<div class="description">
							<?php echo _('Elements'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_settings" onClick="ui.showTab('settings')" class="no-greens">
					<div>
						<img src="assets/icons/settings.svg" title="<?php echo _('Settings'); ?>">
						<div class="description">
							<?php echo _('Design settings') ?>
						</div>
					</div>
				</button>

				<button class="no-mint">
					<div>
						<a href="index.php?c=frontend&m=logout" class="link">
							<img src="assets/icons/logout.svg">
						</a>
						<div class="description">
							<?php echo _('Logged in as') . ' ' .$this->env->user->get_username(); ?>
						</div>
					</div>
				</button>
			</div>
		</div>
		<?php
			include 'src/Views/ImageDB.php';
			include 'src/Views/PublicSharepics.php';
		?>
	</div>
</main>

<dialog id="waiting">
  <p>
	<?php
		echo _('Please wait, while your sharepic is being created. This may take a few seconds.');
	?>
  </p>
</dialog>

<?php
if($this->env->config->get( 'Main', 'tenant' ) === 'mint' && !$this->env->user->get_settings('terms_accepted')) {
	require_once 'src/Views/TermsOfUse.php';
}
?>

<script>
    const config = {
		<?php if ( $pixabayapi = $this->env->config->get( 'Pixabay', 'apikey' ) ) { ?>
        'pixabay': {
            'apikey': '<?php echo $pixabayapi; ?>'
        },
		<?php } ?>
		<?php if ( $mintmediadatabase = $this->env->config->get( 'Mintmediadatabase', 'apikey' ) ) { ?>
		'mintmediadatabase': {
			'apikey': '<?php echo $mintmediadatabase; ?>'
		},
		<?php } ?>
		'tenant': '<?php echo $this->env->config->get( 'Main', 'tenant' ); ?>',
		'url': '<?php echo $this->env->config->get( 'Main', 'url' ); ?>',
		'starttemplate': '<?php echo $starttemplate; ?>',
		'imagedb': '<?php echo $this->env->config->get( 'Main', 'imagedb' ); ?>',
		'username': '<?php echo $this->env->user->get_username(); ?>',
		'env': '<?php echo $this->env->config->get( 'Main', 'env' ); ?>',
		'debug': '<?php echo ( 'local' === $this->env->config->get( 'Main', 'env' ) ) ? 'true' : 'false'; ?>',
		'qrcode': '<?php echo $this->env->config->get( 'Main', 'qrcode' ); ?>',
		'palette':  <?php echo $this->env->user->get_palette(); ?>
	};
	const lang = {
		'Are you sure to delete?': '<?php echo _( 'Are you sure, that you want to delete your sharepic?') ?>',
		'Want to log out?': '<?php echo _( 'Do you want to log out?') ?>',
		'All changes lost': '<?php echo _( 'Please save your sharepic. All changes will be lost.') ?>',
		'Enter prompt for image': '<?php echo _( 'Please enter a text describing your desired image.') ?>',
		'Max reached': '<?php echo _( 'Maximum number of elements reached') ?>',
		'Uploading image': '<?php echo _( 'Uploading your image ...') ?>',
		'Processing image': '<?php echo _( 'Your image is being processed ...') ?>',
		'Image too big': '<?php echo _( 'The image is too big. Max. 15 MB are allowed.') ?>',
		'logged out': '<?php echo _( 'You have automatically been logged out. Please reload the page and log in again.') ?>',
		'You will be logged out soon': '<?php echo _( 'You will be automatically logged out soon. Please either save your work or download it and reload the page.') ?>',
		'Please wait until the image is uploaded': '<?php echo _( 'Please wait until the image is uploaded.') ?>',
		'Max number of lines reached': '<?php echo _( 'Maximum number of lines reached. For longer text, please use additional text element.') ?>',
		'Color added': '<?php echo _( 'Color added') ?>',
		'Please choose a new color first': '<?php echo _('Please choose a new color first'); ?>',
		'One moment': '<?php echo _('One moment please'); ?>',
   };
</script>

</body>
</html>
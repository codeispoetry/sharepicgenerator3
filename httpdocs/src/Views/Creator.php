<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        #canvas{
            zoom: 1 !important;
        }
    </style>
</head>
<body class="<?php echo $body_classes;?>">
    
<header>
    <?php
		$show_my_sharepics = ( $this->config->get( 'Main', 'authenticator' ) === 'greens');
		$menu = 'src/Views/Menu-' . $this->config->get( 'Main', 'menu' );
		if( file_exists( $menu . '.php' ) )
			include $menu . '.php';
		else
			include 'src/Views/Menu-Mint.php';	?>
</header>

<script src="assets/script.js?r=<?php echo rand(); ?>" defer></script>

<main class="main">
	<div class="row">
		<div id="welcome">
				<?php
					require 'src/Views/Message.php';
				?>
			</div>
		<div id="workbench" class="workbench">
			<div style="display: flex; justify-content: center;">
				<div>
					<button onClick="undo.undo()" id="undo" class="no-button">
						<img src="assets/icons/undo.svg" title="<?php echo _('Undo'); ?>">	
					</button>
					<div id="canvas" translate="no">
						<div id="sharepic">
							
						</div>
					</div>
				</div>
			</div>
			<div class="workbench-below">
				<button class="create" style="border-top-right-radius: 0; border-bottom-right-radius:0;" onClick="api.create()">
					<img src="assets/icons/download.svg"> <?php  echo _('Download');?>
				</button>
				<select class="button-dropdown" id="jpg">
					<option value="false">png</option>
					<option value="true">jpg</option>
				</select>

				<div class="message"></div>
			</div>
			<?php
				require 'src/Views/RTE.php';
			?>
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
				<button id="tab_btn_dimensions" onClick="ui.showTab('dimensions')">
					<div>
						<img src="assets/icons/resize.svg" title="<?php echo _('Dimensions'); ?>">
						<div class="description">
							<?php echo _('Dimensions'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_search"  onClick="ui.showTab('search')">
					<div>
						<img src="assets/icons/search_image.svg" title="<?php echo _('Search or create image'); ?>">
						<div class="description">
							<?php echo _('Search or create image'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_background" onClick="ui.showTab('background')" class="active">
					<div>
						<img src="assets/icons/wallpaper.svg" title="<?php echo _('Edit background image'); ?>">
						<div class="description">
							<?php echo _('Edit background image'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_copyright" onClick="ui.showTab('copyright')">
					<div>
						<img src="assets/icons/attribution.svg" title="<?php echo _('Copyright'); ?>">
						<div class="description">
							<?php echo _('Copyright'); ?>
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
				<button id="tab_btn_greentext" onClick="ui.showTab('greentext')">
					<div>
						<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
						<div class="description">
							<?php echo _('Text'); ?>
						</div>
					</div>
				</button>
				<button id="tab_btn_greenaddtext" onClick="ui.showTab('greenaddtext')">
					<div>
						<img src="assets/icons/text-add.svg" title="<?php echo _('Additional text'); ?>">
						<div class="description">
							<?php echo _('Additional text'); ?>
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
				<button id="tab_btn_addpicture"  onClick="ui.showTab('addpicture')">
					<div>
						<img src="assets/icons/add_image.svg" title="<?php echo _('Front picture'); ?>">
						<div class="description">
							<?php echo _('Front picture'); ?>
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

				<button>
					<div>
						<a href="index.php?c=frontend&m=logout" class="link">
							<img src="assets/icons/logout.svg">
						</a>
						<div class="description">
							<?php echo ( $this->user->is_logged_in() ) ? _('Logged in as') . ' ' . $this->user->get_username() : '' ?>
						</div>
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
		echo _('Please wait, while your sharepic is being created. This may take a few seconds.');
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
		'username': '<?php echo $this->user->get_username(); ?>',
	};
	
	const lang = {
		'Are you sure?': '<?php echo _( 'Are you sure?') ?>',
		'All changes lost': '<?php echo _( 'Please save your sharepic. All changes will be lost.') ?>',
		'Enter prompt for image': '<?php echo _( 'Please enter a text describing your desired image.') ?>',
		'Max reached': '<?php echo _( 'Maximum number of elements reached') ?>',
		'Uploading image': '<?php echo _( 'Uploading your image ...') ?>',
		'Processing image': '<?php echo _( 'Your image is being processed ...') ?>',
    };
</script>

</body>
</html>
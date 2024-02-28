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
<body class="<?php echo $body_classes;?>">
    
<header>
    <?php
		include 'src/Views/Menu.php'; 
	?>
</header>

<script src="assets/script.js?r=<?php echo rand(); ?>" defer></script>

<main class="main">
	<div class="row">
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
				<button class="create" onClick="api.create()">
					<img src="assets/icons/download.svg"> <?php  echo _('Download');?>
				</button>
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
				<button id="tab_btn_download" onClick="ui.showTab('download')">
					<div>
						<img src="assets/icons/resize.svg" title="<?php echo _('Download'); ?>">
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
						<img src="assets/icons/wallpaper.svg" title="<?php echo _('Image'); ?>">
						<div class="description">
							<?php echo _('Image'); ?>
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
				<button id="tab_btn_freetext" onClick="ui.showTab('freetext')" class="no-greens">
					<div>
						<img src="assets/icons/text.svg" title="<?php echo _('Text'); ?>">
						<div class="description">
							<?php echo _('Text'); ?>
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
		echo _('Please wait ...');
	?>
  </p>
</dialog>

<style>
	.ql-font-Roboto-Light {
		font-family: 'Roboto', sans-serif;
	}
	.ql-font-Baloo2 {
		font-family: 'Baloo2', cursive;
	}
	.ql-font-Calibri {
		font-family: 'Calibri', sans-serif;
	}

	[data-value="Baloo2"]::before {
		content: 'Baloo2' !important;
	}

	[data-value="Roboto-Light"]::before {
		content: 'Roboto' !important;
	}

	[data-value="Calibri"]::before {
		content: 'Calibri' !important;
	}
</style>

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
		'Enter prompt for image': '<?php echo _( 'Please enter a text describing your desired image.') ?>',
		'Max reached': '<?php echo _( 'Maximum number of elements reached') ?>',
    };
</script>

</body>
</html>
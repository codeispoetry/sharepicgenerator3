<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="/assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
    <link href="/node_modules/quill/dist/quill.bubble.css" rel="stylesheet">
    <script src="/node_modules/quill/dist/quill.js"></script>

    <style>
        #canvas{
            zoom: 1 !important;
        }
    </style>
</head>
<body>
    
<header>
    <?php include 'src/Views/Menu.php'; ?>

    <div style="display: flex; align-items: center">
        <div style="margin-right: 1em" class="flags">
            <img src="/assets/icons/de.svg" alt="<?php echo _('German');?>" title="<?php echo _('German');?>" data-lang="de" style="margin-right: 5px;">
            <img src="/assets/icons/en.svg" alt="<?php echo _('English');?>" title="<?php echo _('English');?>" data-lang="en">
        </div>
        <?php if( $this->user->is_logged_in() ): ?>
            <div style="margin-right: 1em">
                <?php  echo _('Logged in as');?> <?php echo $this->user->get_username(); ?>
            </div>
            <a href="/index.php/frontend/logout" class="link" style="margin-right:1em">
                <?php  echo _('Logout');?>
                <img src="/assets/icons/logout.svg" style="height: 1em;">
			</a>
        <?php endif; ?>
    </div>
</header>

<script src="/assets/script.js?r=<?php echo rand(); ?>" defer></script>
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
			<div id="adder">
				<div class="add_sign"><img src="/assets/icons/add_circle.svg"></div>
				<ul class="add_elements">
					<li data-item="eyecatcher"><?php  echo _('Eyecatcher');?></li>
				</ul>
			</div>
			<div id="canvas">
				<div id="sharepic">
					
				</div>
			</div>
			<div id="inlinecockpit">
				<div class="ictop">
					<button class="create flat" data-click="api.create"><img src="/assets/icons/download.svg"> <?php  echo _('Download');?></button>
				</div>
				<div>
					<button class="save flat" data-click="api.save"><img src="/assets/icons/save.svg"> <?php  echo _('Save');?></button>
				</div>
			</div>
		</div>
		<div id="cockpit" class="cockpit">
			<?php
			foreach ( glob( "src/Views/Cockpit/*.php" ) as $filename ) {
				include $filename;
			}
			?>
		</div>
		<?php
			include 'src/Views/Pixabay.php';
		?>
	</div>
</main>

<script>
    const config = {
        'pixabay': {
            'apikey': '<?php echo $this->config->get( 'Pixabay', 'apikey' ); ?>'
        }
    };
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body class="home <?php echo $body ?? ''; ?>">
    
<main class="main">
	<div id="title">
		<h1><?php  echo _('Sharepicgenerator');?></h1>
		<p id="description">
			<?php
				echo _('Create sharepics for your social media channels.');
			?>
		</p>
	</div>
	<div id="loginboxes">
		<div id="loginbox">
			<?php
				echo '<h3>' . _('Login') . '</h3>';
			?>

			<a href="index.php?c=frontend&m=create" class="button">
				<img src="assets/icons/login.svg" style="margin-right: 10px">
				<?php  echo _('login with Mint ID');?>
			</a>

		</div>
	
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

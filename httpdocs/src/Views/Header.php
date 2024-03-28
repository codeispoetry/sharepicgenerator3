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
    
<header>
    <?php
		$show_my_sharepics = ( $this->config->get( 'Main', 'authenticator' ) === 'greens');
		$menu = 'src/Views/Menu-' . $this->config->get( 'Main', 'menu' );
		if( file_exists( $menu . '.php' ) )
			include $menu . '.php';
		else
			include 'src/Views/Menu-Mint.php';	?>
</header>

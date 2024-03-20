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
<nav>

    <div style="display: flex; align-items: center">
        <a href="index.php" class="menu-link">
            <?php  echo _('Home');?>
        </a>
        <a href="index.php?c=frontend&m=view&view=Imprint" class="menu-link">
            <?php  echo _('Imprint');?>
        </a>
        <a href="index.php?c=frontend&m=view&view=Privacy" class="menu-link">
            <?php  echo _('Privacy');?>
        </a>
    </div>
</header>

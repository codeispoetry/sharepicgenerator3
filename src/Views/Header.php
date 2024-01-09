<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo _( 'Sharepicgenerator'); ?></title>
    <link rel="stylesheet" href="assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body class="<?php echo $body ?? ''; ?>">
    
<header>
   <nav>
        <a href="index.php"><?php echo _('Home');?></a>
        <a href="index.php?c=frontend&m=create"><?php echo _('Create'); ?></a>

   </nav>

    <div style="display: flex; align-items: center">
        <a href="#"><?php echo _('Imprint');?></a> 
            <span style="padding: 0 0.3em;">|</span>
        <a href="#"><?php echo _('Privacy');?></a>

    </div>
</header>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo _( 'Sharepicgenerator'); ?></title>
    <link rel="stylesheet" href="/assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">
</head>

<body class="<?php echo $body ?? ''; ?>">
    
<header>
   <nav>
        <a href="/"><?php echo _('Home');?></a>
   </nav>

    <div style="display: flex; align-items: center">
        <a href="#"><?php echo _('Imprint');?></a> 
            <span style="padding: 0 0.3em;">|</span>
        <a href="#"><?php echo _('Privacy');?></a>

    </div>
</header>

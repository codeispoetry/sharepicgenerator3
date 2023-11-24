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

    <div>
        <?php if( $this->user->is_logged_in() ): ?>
            <button id="logout" class="link">Logout</button>
            Eingeloggt als <?php echo $this->user->get_username(); ?>
        <?php endif; ?>
    </div>
</header>

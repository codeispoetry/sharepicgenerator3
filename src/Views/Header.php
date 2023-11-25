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
        <div style="margin-right: 1em">
            <img src="/assets/icons/de.svg" alt="<?php echo _('German');?>" title="<?php echo _('German');?>" data-lang="de" style="height: 15px;cursor:pointer;margin-right: 5px;">
            <img src="/assets/icons/en.svg" alt="<?php echo _('English');?>" title="<?php echo _('English');?>" data-lang="en" style="height: 15px;cursor:pointer;">
        </div>
        <?php if( $this->user->is_logged_in() ): ?>
            <button id="logout" class="link" style="margin-right:1em"><?php  echo _('Logout');?></button>
            <?php  echo _('Logged in as');?> <?php echo $this->user->get_username(); ?>
        <?php endif; ?>
    </div>
</header>

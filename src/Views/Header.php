<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="/assets/styles.css">
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
            <button id="logout">Logout</button>
            Eingeloggt als <?php echo $this->user->get_username(); ?>
        <?php endif; ?>
    </div>
</header>

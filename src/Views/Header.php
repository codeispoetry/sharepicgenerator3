<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    
<header>
    <nav>
        <a href="/">Home</a>
        <button id="create">Create</button>
        <button id="reset">Reset</button>
        <button id="load_latest">Load latest</button>
        <?php if( $this->user->is_logged_in() ): ?>
            <button id="logout">Logout</button>
            Eingeloggt als <?php echo $this->user->get_username(); ?>
        <?php endif; ?>
    </nav>
</header>

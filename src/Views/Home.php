<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharepicgenerator</title>
    <link rel="stylesheet" href="/assets/styles.css?v=<?php echo filemtime('assets/styles.css');?>">

</head>
<body style="display: flex; align-items: center; justify-content: center;">
	<main class="main">
		<div class="row">
			<div class="">
				<h1><?php  echo _('Sharepicgenerator');?></h1>
				<form method="post" action="index.php/frontend/create">
					<input type="text" name="username" id="username" placeholder="<?php  echo _('username');?>" value="tom">
					<input type="submit" name="submit" value="<?php  echo _('login');?>">
				</form>
			</div>
			
		</div>	
	</main>
</body>
</html>








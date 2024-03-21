<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="height: 100%; display: flex; justify-content: center; align-items: center;">
	<div class="row">
		<div class="">
			<h1><?php  echo $title;?></h1>
			<p>
                <?php echo $message;?>
            </p>
		</div>
		
	</div>	
</main>

<?php include_once './src/Views/Footer.php'; ?>

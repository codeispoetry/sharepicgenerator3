<?php include_once './src/Views/Header.php'; ?>
<main class="main">
	<div id="title">
		<h1><?php  echo _('Sharepicgenerator');?></h1>
		<p id="description">
			<?php
				echo _('Sharepicgenerator is a free service that allows you to create images with text for social media. You can use it to create images for Facebook, X, Instagram, LinkedIn, and other social networks. You can also use it to create images for your blog posts, Facebook ads, and more.');
			?>
		</p>
	</div>
	<div id="loginboxes">
		<div id="loginbox">
			<?php
				echo '<h3>' . _('Login') . '</h3>';
				include_once './src/Views/Components/Forms/Login.php'; 
				echo _('Not registered yet?');
				echo ' <a href="#" onclick="showRegisterBox();">'._('Register').'</a>';
				echo '<br>';
				echo ' <a href="#" onclick="showPasswordBox();">'._('Forgot password?').'</a>';

				if ( 'Greens' === $config->get( 'Main', 'menu' ) ) {
					printf( '<a href="index.php?auth=config">%s</a>', _('login with Green Net'));
				}
			?>
		</div>
		<div id="registerbox" style="display: none">
			<?php
				echo '<h3>' . _('Register') . '</h3>';
				include_once './src/Views/Components/Forms/Register.php';
				echo _('Already registered?');
				echo ' <a href="#" onclick="showLoginBox();">'._('Login').'</a>';
			?>
		</div>
		<div id="passwordbox" style="display: none">
			<?php
				echo '<h3>' . _('Password reset') . '</h3>'; 
				include_once './src/Views/Components/Forms/RequestPassword.php';
				echo _('Already registered?');
				echo ' <a href="#" onclick="showLoginBox();">'._('Login').'</a>';
			?>
		</div>
	</div>	
</main>

<script>
	function showRegisterBox(){
		document.getElementById('loginbox').style.display = 'none';
		document.getElementById('registerbox').style.display = 'block';
		document.getElementById('passwordbox').style.display = 'none';
	}
	function showLoginBox(){
		document.getElementById('loginbox').style.display = 'block';
		document.getElementById('registerbox').style.display = 'none';
		document.getElementById('passwordbox').style.display = 'none';
	}
	function showPasswordBox(){
		document.getElementById('loginbox').style.display = 'none';
		document.getElementById('registerbox').style.display = 'none';
		document.getElementById('passwordbox').style.display = 'block';
	}
</script>
<?php include_once './src/Views/Footer.php'; ?>

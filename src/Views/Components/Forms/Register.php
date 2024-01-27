<form method="post" action="index.php?c=frontend&m=register">
    <input type="email" name="register_mail" placeholder="<?php  echo _('email');?>" autocomplete="email"><br>
    
    <label for="register_terms" style="display: flex; flex-direction: row;align-items: flex-start;">
        <input type="checkbox" name="register_terms" id="register_terms" value="1" required>
        <p style="margin: 0">
        <?php
           printf( _('I have read the %sterms of condition%s and accept them.</a>'), 
                '<a href="#" onClick="document.getElementById(`terms_condition`).showModal();">', 
                '</a>'
            );
        ?>
        </p>
    </label>
    <br>
    <input type="submit" name="submit" value="<?php  echo _('register');?>">
</form>

<dialog id="terms_condition" style="margin: 30px">
    <?php
    	include_once './src/Views/Pages/Privacy.html';
	?>
    <button onClick="document.getElementById('terms_condition').close();">
        <?php echo _('close');?>
    </button>
</dialog>
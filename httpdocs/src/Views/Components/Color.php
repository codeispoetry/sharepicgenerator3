<div class="color" id="<?php echo $color->id; ?>">   
    <div class="palette">
        <button 
            class="no-button" 
            data-blueprint="true" 
            style="background-color: #ffffff" 
            onClick="<?php echo $color->onclick; ?>(this.style.backgroundColor);">
        </button>
    </div>
    
    <input type="color" 
        value="<?php echo $color->value; ?>" 
        class="" 
        id="<?php echo $color->id; ?>" 
        oninput="<?php echo $color->oninput; ?>">

    <button 
        class="colorpicker" 
        onclick="this.previousElementSibling.click();">
    </button>
</div>

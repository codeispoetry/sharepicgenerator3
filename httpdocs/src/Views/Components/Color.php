<div class="color" id="<?php echo $color->id; ?>">
    <input type="color" 
        value="<?php echo $color->value; ?>" 
        class="" 
        id="<?php echo $color->id; ?>" 
        oninput="<?php echo $color->oninput; ?>">
    
    <div class="palette">
        <button class="no-button" data-blueprint="true" style="background-color: #ffffff" onClick="<?php echo $color->onclick; ?>(this.style.backgroundColor);"></button>
    </div>
</div>

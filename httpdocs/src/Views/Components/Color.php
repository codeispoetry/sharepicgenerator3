<div class="color">
    <input type="color" 
        value="<?php echo $color->value; ?>" 
        class="" 
        id="<?php echo $color->id; ?>" 
        oninput="<?php echo $color->oninput; ?>">
    
    <div class="palette">
        <button class="no-button bg-tanne" onClick="<?php echo $color->onclick; ?>('#005437');" title="<?php echo _("Tanne"); ?>"></button>
        <button class="no-button bg-klee" onClick="<?php echo $color->onclick; ?>('#008939');" title="<?php echo _("Klee"); ?>"></button>
        <button class="no-button bg-gras" onClick="<?php echo $color->onclick; ?>('#8abd24');" title="<?php echo _("Gras"); ?>"></button>
        <button class="no-button bg-sand" onClick="<?php echo $color->onclick; ?>('#f5f1e9');" title="<?php echo _("Sand"); ?>"></button>
    </div>
</div>

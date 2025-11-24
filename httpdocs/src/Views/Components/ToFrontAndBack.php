<section id="cockpit_tofrontandback" class="selected_only">
    <h3><?php  echo _('Order');?></h3>

    <div class="">
        <button class="with-icon" onClick="component.toFront(this)" title="<?php  echo _('to front');?>">
            <div class="icon">
                <img src="assets/icons/to_front.svg" alt="<?php  echo _('to front');?>" />
            </div>
            <?php  echo _('to front');?>
        </button>
        <button class="with-icon" onClick="component.toBack(this)" title="<?php  echo _('to back');?>">
            <div class="icon">
                <img src="assets/icons/to_back.svg" alt="<?php  echo _('to back');?>" />
            </div>
            <?php  echo _('to back');?>
        </button>
    </div>
</section>

<?php if (!isset($nodelete)) {?>
<section class="selected_only btn_delete">
    <button class="delete" onClick="component.delete()" title="<?php  echo _('delete');?>">
        <?php  
        echo $delete_button_text . ' ' .  _('delete');
        $delete_button_text = '';
        ?>
    </button>
</section>
<?php } ?>

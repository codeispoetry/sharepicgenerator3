<section class="selected_only">
    <h3><?php  echo _('Order');?></h3>

    <div class="horizontal">
        <button class="to-front no-button" onClick="component.toFront(this)" title="<?php  echo _('to front');?>">
            <img src="assets/icons/to_front.svg" alt="<?php  echo _('to front');?>" />
        </button>
        <button class="to-back no-button" onClick="component.toBack(this)" title="<?php  echo _('to back');?>">
            <img src="assets/icons/to_back.svg" alt="<?php  echo _('to back');?>" />
        </button>
    </div>
</section>

<?php if (!isset($nodelete)) {?>
<section class="selected_only btn_delete">
    <button class="outline" onClick="component.delete()" title="<?php  echo _('delete');?>">
        <img src="assets/icons/delete.svg" alt="<?php  echo _('delete');?>" />
        <?php  echo _('delete');?>
    </button>
</section>
<?php } ?>

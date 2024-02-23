<section class="selected_only">
    <div class="row">
        <button class="to-front no-button" onClick="component.toFront(this)" title="<?php  echo _('to front');?>">
            <img src="assets/icons/to_front.svg" alt="<?php  echo _('to front');?>" />
        </button>
        <button class="to-back no-button" onClick="component.toBack(this)" title="<?php  echo _('to back');?>">
            <img src="assets/icons/to_back.svg" alt="<?php  echo _('to back');?>" />
        </button>
        <button class="no-button" onClick="cockpit.target.remove()" title="<?php  echo _('delete');?>">
            <img src="assets/icons/delete.svg" alt="<?php  echo _('delete');?>" />
        </button>
    </div>
</section>

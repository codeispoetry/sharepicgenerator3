<div id="rte">

    <input type="color" onchange="rte.setStyle('color', this.value)"
             title="<?php echo _('color'); ?>"
    >

    <button style="display: none" onClick="rte.setStyle('textShadow', '2px 2px 5px rgba(0,0,0,0.5)')"><?php echo _("Shadow"); ?></button>

    <select 
        onchange="rte.setStyle('fontSize', this.value)"
        title="<?php echo _('font size'); ?>"
    >
        <option value="66%">small</option>
        <option value="100%">medium</option>
        <option value="150%">large</option>
    </select>

    <select 
        onchange="rte.setStyle('fontWeight',this.selectedOptions[0].dataset.weight);rte.setStyle('fontStyle',this.selectedOptions[0].dataset.style)"
        title="<?php echo _('font appearance'); ?>"
    > 
        <option data-weight="normal" data-style="normal">normal</option>
        <option data-weight="bold" data-style="normal">bold</option>
        <option data-weight="normal" data-style="italic">italic</option>
        <option data-weight="bold" data-style="italic">bold und italic</option>
    </select>

    <select onchange="rte.setStyle('fontFamily', this.value)" title="<?php echo _('font'); ?>">
        <option value="Calibri">Calibri</option>
        <option value="Baloo2">Baloo</option>
        <option value="Roboto">Roboto</option>
    </select>


    <button onClick="rte.clearFormat()" class="pictogram" style="margin-left: auto" title="<?php echo _('clear format'); ?>">
        <img src="assets/icons/format_clear.svg">
    </button>

    <button onClick="rte.showSource()" id="show_source" class="pictogram" title="<?php echo _('show source'); ?>">
        <img src="assets/icons/code.svg">
    </button>
    <button onClick="rte.showRTE()" id="show_rte" style="display: none" class="pictogram"  title="<?php echo _('show richt text editor'); ?>">
        <img src="assets/icons/ink_marker.svg">
    </button>



    <textarea id="source" style="display: none"></textarea>
</div>
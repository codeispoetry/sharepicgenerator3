<section class="mainsection" id="cockpit_copyright">
    <a href="#" onClick="ui.showTab('background')">
        <
    </a>
    <h2><?php  echo _('Copyright');?></h2>

    <section>
        <button onClick="component.add('copyright')" id="add_copyright"><?php  echo _('Add copyright');?></button>
    </section>
    
    <section class="selected_only">
        <h3><?php  echo _('Size');?></h3>
        <input type="range" min="10" max="50" value="20" class="slider" id="copyright_size" oninput="copyright.setSize(this)">
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Color');?></h3>
        <?php
            $color = new stdClass();
            $color->value = "#ffffff";
            $color->id = "copyright_color";
            $color->oninput = "copyright.setFontColor(this.value)";
            $color->onclick = "copyright.setFontColor";
            require ("./src/Views/Components/Color.php"); 
        ?>
    </section>

    <section class="selected_only">
        <h3><?php  echo _('Position');?></h3>
        <div style="display: flex; justify-content:space-between;">
            <button class="no-button" onclick="copyright.setPostion(1);">
                <?php  echo _('bottom left');?>
            </button>
            <button class="no-button" onclick="copyright.setPostion(2);">
                <?php  echo _('bottom right');?>
            </button>
        </div>
    </section>
</section>

<script>
    class Copyright{
        setSize(input){
            cockpit.target.style.fontSize = input.value + 'px';
            undo.commit()
        }

        setFontColor(color) {      
            document.getElementById('copyright_color').value = color
            cockpit.target.style.color = color
            undo.commit()
        }

        setPostion( pos ) {
            switch( pos ) {
                case 2:
                    cockpit.target.style.bottom = '8px';
                    cockpit.target.style.left = 'auto';
                    cockpit.target.style.right = '10px';
                    cockpit.target.style.transform = 'rotate(0deg)';
                    break;
                default:
                    cockpit.target.style.bottom = '0';
                    cockpit.target.style.right = 'auto'
                    cockpit.target.style.left = '10px';
                    cockpit.target.style.transform = 'rotate(-90deg)';
                    break;
            }    
            undo.commit()
        }
    }
    const copyright = new Copyright();
</script>





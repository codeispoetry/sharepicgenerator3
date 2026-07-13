<section class="mainsection" id="cockpit_ndsclaim">
    <h2><?php  echo _('NDS Claim');?></h2>
   
    <section id="eyecatcher_colors" class="selected_only">
        
        <div class="color" id="nds_claim_bgcolor">   
    
            <h3><?php echo _('Colors');?></h3>
            <div class="standard-palette">
                <?php
                $standard_colors = array(
                   '#8abd24', '#005538'                
                   );
                
                foreach($standard_colors as $standard_color){
                    printf('<button 
                                class="no-button" 
                                style="background-color: %s" 
                                onClick="ndsClaim.setBgColor(this.style.backgroundColor);">
                            </button>', 
                            $standard_color
                        );
                    }
                ?>
            
            </div>
        </div>

    </section>

<?php $nodelete = true; require ("./src/Views/Components/ToFrontAndBack.php"); ?>
    
</section>


<script>
    class NDSClaim{
        setSize( input ) {
            cockpit.target.style.width = input.value + 'px';
            cockpit.target.style.height = input.value + 'px';
        }

        setBgColor( color ) {
            document.getElementById('claim_nds').style.fill=color
        }

    }
    const ndsClaim = new NDSClaim();
</script>

